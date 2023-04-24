<?php

namespace Workspace\Commands;

use Illuminate\Console\Command;
use Database\Seeders\HostnamesTableSeeder;
use Database\Seeders\HostnamesBreadSeeder;
use Database\Seeders\PermissionAdminRoleTableSeeder;

class InstallWorkspace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workspace:install {name=Workspace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Workspace app';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing a new Workspace app');

        $name = $this->argument('name');
         
        $this->info('Setting your app name to '.$name);
        $this->updateEnvVall('APP_NAME',$name);


        $progressbar = $this->output->createProgressBar(4);
        $progressbar->start();

        \Artisan::call('db:wipe');
        $progressbar->advance();
        // \Artisan::call('migrate:fresh');
        \Artisan::call('migrate', ['--path' => '/core/database/migrations/hostnames/2017_01_01_000003_tenancy_websites.php' ]);
        \Artisan::call('migrate', ['--path' => '/core/database/migrations/hostnames/2017_01_01_000005_tenancy_hostnames.php' ]);
        
        $progressbar->advance();
        \Artisan::call('migrate');
        $progressbar->advance();
        //\Artisan::call('voyager:install', ['--with-dummy' => true ]);

        
        \Artisan::call('db:seed');
        \Artisan::call('db:seed', ['--class' => HostnamesTableSeeder::class] );
        \Artisan::call('db:seed', ['--class' => HostnamesBreadSeeder::class] );
        
        $progressbar->advance();
        \Artisan::call('db:seed', ['--class' => PermissionAdminRoleTableSeeder::class] );
        
        // \Artisan::call('voyager-site:install');
        $progressbar->finish();
        $this->newLine();
        $this->info($name.' is successfully installed. Enjoy!');
        $this->newLine();
        return Command::SUCCESS;
    }

    private function updateEnvVall($key, $value){
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));
        }
    }
}
