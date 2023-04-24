<?php

namespace Workspace\Commands;

use Illuminate\Console\Command;

use Workspace\Tenant as TenantModel;
use Hyn\Tenancy\Environment;
use Workspace\Website;
use Workspace\Hostname; 
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;

class CreatingTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {fqdn}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a new tenant';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $environment = app(Environment::class);
        $fqdn = $this->argument('fqdn');
        
        if (in_array($fqdn, config('workspace.ban_app_name'))){
            $this->error('This app name is ban! Try a different app name.');
            return Command::FAILURE;
        }

        $fqdn = $this->validate_name($fqdn);
        //dd($fqdn);

        $progressbar = $this->output->createProgressBar(5);
        $progressbar->start();
        $this->newLine();

        config(['app.name' => ucfirst($this->argument('fqdn'))]);
        config(['database.default' => 'tenant']);
        config(['voyager.storage.disk' => 'tenant']);
        config(['webpush.database_connection' => 'tenant']);
        $hostname = Hostname::where('fqdn', $fqdn)->first();
         
        if(isset($hostname->id)){
            $this->info('Updateing Tenant : '.$fqdn);

            $website = Website::find($hostname->website_id);
            $environment->hostname($hostname);
            $environment->tenant($website); 
            app(Environment::class)->tenant($website);

            $this->info($website);

           \Artisan::call('db:wipe');
        }else{
            $this->info('Creating a new tenant');
            $website = new Website;
            app(WebsiteRepository::class)->create($website);

            $hostname = new Hostname;
            $hostname->fqdn = $fqdn;
            $hostname = app(HostnameRepository::class)->create($hostname);
            app(HostnameRepository::class)->attach($hostname, $website);
            app(Environment::class)->tenant($hostname->website);


            $this->info('New Tenant Created : '.$fqdn);

            $this->info($environment->hostname());
            $this->info($environment->tenant());

        }

        $progressbar->advance();
        $this->newLine();

        $tenant_path = storage_path('app/tenancy/tenants/'.app(\Hyn\Tenancy\Website\Directory::class)->path());
        $theme_path = $tenant_path.'resources/views/themes';
        \File::copyDirectory(resource_path('views/themes'), $theme_path);

        $progressbar->advance();
        $this->newLine();

        $this->info('Database Migrating');
        \Artisan::call('migrate');

        $progressbar->advance();
        $this->newLine();

        $this->info('Database Seeding');
        \Artisan::call('db:seed');
        // \Artisan::call('voyager-site:install');

        $progressbar->advance();
        $this->newLine();

        
        $module_path = $tenant_path.'resources/modules/'; 
        if(!\File::isDirectory($module_path)){
            \File::makeDirectory($module_path, 0777, true, true);
        }
        
        $json = config('workspace.default_modules',[]);
        
        foreach($json as $key => $status) {
           \File::copyDirectory(base_path('modules/').$key, $module_path.$key);
        }
        \Storage::disk('tenant')->put('resources/modules/modules_statuses.json', json_encode($json));

        $public_path = $tenant_path.'themes';
        \File::copyDirectory(public_path('themes'), $public_path);

        $progressbar->advance();
        $this->newLine();

        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');

        $progressbar->finish();
        $this->info('');
        $this->info($fqdn.' is successfully installed with Voyager. Enjoy!');
    }


    private function validate_name($name){
        $fqdn = str_replace(".","",trim($name));
        $fqdn = str_replace(' ', '-', $fqdn);
        $fqdn = preg_replace('/[^A-Za-z0-9\-]/', '', $fqdn);
        $fqdn = $fqdn .'.'. env('ROOT_DOMAIN_NAME');

        return $fqdn;
    }
}