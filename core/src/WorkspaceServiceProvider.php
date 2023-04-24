<?php

namespace Workspace;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\Relation;
use Workspace\Menu;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use Workspace\Facades\Workspace as WorkspaceFacade;
use Workspace\TokenGuard;
use Livewire\Livewire;

use Hyn\Tenancy\Environment;
use TCG\Voyager\Facades\Voyager;
use Workspace\Actions\TenantViewAction;
use Workspace\Actions\TenantLoginAction;
use Workspace\Actions\TenantDeleteAction;
use TCG\Voyager\Actions\ViewAction;
use TCG\Voyager\Actions\DeleteAction;

class WorkspaceServiceProvider extends ServiceProvider
{
    private $models = [
        'Theme',
        'ThemeOptions',
    ];

	public function register(){
        
	    $loader = AliasLoader::getInstance();
	    $loader->alias('Workspace', WorkspaceFacade::class);

	    $this->app->singleton('workspace', function () {
	        return new Workspace();
	    });

        

	    $this->loadHelpers();

        $this->loadLivewireComponents();

	    $workspaceMiddleware = [
	    	\Illuminate\Auth\Middleware\Authenticate::class,
    		\Workspace\Http\Middleware\TrialEnded::class,
    		\Workspace\Http\Middleware\Cancelled::class,
    	];

    	$this->app->router->aliasMiddleware('token_api', \Workspace\Http\Middleware\TokenMiddleware::class);
	    $this->app->router->pushMiddlewareToGroup('web', \Workspace\Http\Middleware\WorkspaceMiddleware::class);
        $this->app->router->pushMiddlewareToGroup('web', \Workspace\Http\Middleware\InstallMiddleware::class);

	    $this->app->router->middlewareGroup('workspace', $workspaceMiddleware);

        if ( request()->is(config('voyager.prefix')) || request()->is(config('voyager.prefix').'/*') || app()->runningInConsole() ) {

            try {
                DB::connection()->getPdo();
                $this->addThemesTable();
                $this->addWebsitesTable();
                $this->addHostnamesTable();
            } catch (\Exception $e) {
                \Log::error("Error connecting to database: ".$e->getMessage());
            }

            // app(Dispatcher::class)->listen('voyager.menu.display', function ($menu) {
            //     $this->addThemeMenuItem($menu);
            //     $this->addModuleMenuItem($menu);
            // });

            app(Dispatcher::class)->listen('voyager.admin.routing', function ($router) {
                $this->addThemeRoutes($router);
            });
        }

        $this->publishes([dirname(__DIR__).'/config/themes.php' => config_path('themes.php')], 'themes-config'); 

        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommands();
        }


	}

	public function boot(Router $router, Dispatcher $event){
        Schema::defaultStringLength(191);

        Voyager::useModel('Menu', Menu::class);
        
		Relation::morphMap([
		    'users' => config('workspace.user_model')
		]);

		if(!config('workspace.show_docs')){
			Gate::define('viewLarecipe', function($user, $documentation) {
	            	return true;
	        });
	    }

        $this->loadViewsFrom(__DIR__.'/../docs/', 'docs');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'workspace');
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadBladeDirectives();

        try{
            $this->loadViewsFrom(__DIR__.'/../resources/views/themes', 'themes');
            $theme = '';
            if (Schema::hasTable('themes')) {
                $theme = $this->rescue(function () {
                    return \Workspace\Theme::where('active', '=', 1)->first();
                });
                if(Cookie::get('theme')){
                    $theme_cookied = \Workspace\Theme::where('folder', '=', Cookie::get('theme'))->first();
                    if(isset($theme_cookied->id)){
                        $theme = $theme_cookied;
                    }
                }
            }
            view()->share('theme', $theme);
            $this->themes_folder = config('themes.themes_folder', resource_path('views/themes'));
            $this->loadDynamicMiddleware($this->themes_folder, $theme);
            if (isset($theme)) {
                $this->loadViewsFrom($this->themes_folder.'/'.@$theme->folder, 'theme');
            }
            $this->loadViewsFrom($this->themes_folder, 'themes_folder');
        } catch(\Exception $e){
            return $e->getMessage();
        }

       

        $env = app(Environment::class);

        $isSystem = true; 
        if (PHP_SAPI != 'cli') {
            $hostname = \Workspace\Hostname::where('fqdn','=',$this->app->request->getHost())->first();
            $this->app->request['appname'] = $hostname->app_name;
            //dd($hostname->app_name);
            if(isset($hostname->website_id) && $fqdn = $hostname->fqdn){
                if (\Workspace\Tenant::getRootFqdn() !== $fqdn ) {
                    $website = \Workspace\Website::find($hostname->website_id);
                    $env->hostname($hostname);
                    $env->tenant($website);
                    
                    //config(['app.name' => $hostname->app_name]); 
                    config(['app.asset_url' => 'storage']);
                    config(['database.default' => 'tenant']);
                    config(['voyager.storage.disk' => 'tenant']);

                    config(['cache.prefix' => $website->uuid.'_cache_']);
                    config(['webpush.database_connection' => 'tenant']);

                    
                    $tenant_path = storage_path('app/tenancy/tenants/'.$website->uuid);
                    $theme_path = $tenant_path.'/resources/views/'.theme_folder();
                    config(['themes.themes_folder' => $theme_path]);
                    $this->loadViewsFrom($theme_path, 'theme');

                    $module_path = $tenant_path.'/resources/modules';
                    config(['modules.cache.key' => 'laravel-modules-'.$website->uuid]);
                    config(['modules.paths.modules' => $module_path]);
                    config(['modules.activators.file.statuses-file' => $module_path.'/modules_statuses.json']);
                    config(['modules.activators.file.cache-key' => $website->uuid.'.activator.installed']);

                    
                    $this->app->extend('url', function (\Illuminate\Routing\UrlGenerator $urlGenerator) {
                        return new Routing\UrlGenerator(
                            $this->app->make('router')->getRoutes(),
                            $urlGenerator->getRequest(),
                            $this->app->make('config')->get('app.asset_url')
                        );
                    });
                    

                    $isSystem = false; 

                }
            }
        }

       //dd($this->app->request['appname']);

        if ($isSystem) {
            //$this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations/hostnames'));
            Voyager::addAction(TenantLoginAction::class);
            Voyager::replaceAction(ViewAction::class, TenantViewAction::class);
            Voyager::replaceAction(DeleteAction::class, TenantDeleteAction::class);
        }


	}

    protected function registerConsoleCommands()
    {
        $this->commands(Commands\InstallWorkspace::class);
        $this->commands(Commands\CreatingTenant::class);
        $this->commands(Commands\EcommerceInstall::class);
    }

	protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    protected function loadMiddleware()
    {
        foreach (glob(__DIR__.'/Http/Middleware/*.php') as $filename) {
            require_once $filename;
        }
    }

    protected function loadBladeDirectives(){

        // Subscription Directives

        Blade::directive('subscribed', function ($plan) {
            return "<?php if (!auth()->guest() && auth()->user()->subscribed($plan)) { ?>";
        });

        Blade::directive('notsubscribed', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endsubscribed', function () {
            return "<?php } ?>";
        });


        // Subscriber Directives

        Blade::directive('subscriber', function () {
            return "<?php if (!auth()->guest() && auth()->user()->subscriber()) { ?>";
        });

        Blade::directive('notsubscriber', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endsubscriber', function () {
            return "<?php } ?>";
        });


        // Trial Directives

        Blade::directive('trial', function ($plan) {
            return "<?php if (!auth()->guest() && auth()->user()->onTrial()) { ?>";
        });

        Blade::directive('nottrial', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endtrial', function () {
            return "<?php } ?>";
        });

        // home Directives

        Blade::directive('home', function () {
            $isHomePage = false;

            // check if we are on the homepage
            if ( request()->is('/') ) {
                $isHomePage = true;
            }

            return "<?php if ($isHomePage) { ?>";
        });

        Blade::directive('nothome', function(){
            return "<?php } else { ?>";
        });


        Blade::directive('endhome', function () {
            return "<?php } ?>";
        });


        Blade::directive('workspaceCheckout', function(){
            return '{!! view("workspace::checkout")->render() !!}';
        });

        // role Directives

        Blade::directive('role', function ($role) {
            return "<?php if (!auth()->guest() && auth()->user()->hasRole($role)) { ?>";
        });

        Blade::directive('notrole', function () {
            return "<?php } else { ?>";
        });


        Blade::directive('endrole', function () {
            return "<?php } ?>";
        });
    }

    private function loadLivewireComponents(){
        Livewire::component('workspace.settings.security', \Workspace\Http\Livewire\Settings\Security::class);
        Livewire::component('workspace.settings.api', \Workspace\Http\Livewire\Settings\Api::class);
        Livewire::component('workspace.settings.plans', \Workspace\Http\Livewire\Settings\Plans::class);
        Livewire::component('workspace.settings.subscription', \Workspace\Http\Livewire\Settings\Subscription::class);
        Livewire::component('workspace.settings.invoices', \Workspace\Http\Livewire\Settings\Invoices::class);
    }

    public function addThemeRoutes($router)
    {
        $namespacePrefix = '\\Workspace\\Http\\Controllers\\';
        $router->get('themes', ['uses' => $namespacePrefix.'ThemesController@index', 'as' => 'theme.index']);
        $router->get('themes/activate/{theme}', ['uses' => $namespacePrefix.'ThemesController@activate', 'as' => 'theme.activate']);
        $router->get('themes/options/{theme}', ['uses' => $namespacePrefix.'ThemesController@options', 'as' => 'theme.options']);
        $router->post('themes/options/{theme}', ['uses' => $namespacePrefix.'ThemesController@options_save', 'as' => 'theme.options.post']);
        $router->get('themes/options', function () {
            return redirect(route('voyager.theme.index'));
        });
        $router->delete('themes/delete', ['uses' => $namespacePrefix.'ThemesController@delete', 'as' => 'theme.delete']);
    }

    // public function addThemeMenuItem(Menu $menu)
    // {
    //     if ($menu->name == 'admin') {
    //         $url = route('voyager.theme.index', [], false);
    //         $menuItem = $menu->items->where('url', $url)->first();
    //         if (is_null($menuItem)) {
    //             $menu->items->add(MenuItem::create([
    //                 'menu_id' => $menu->id,
    //                 'url' => $url,
    //                 'title' => 'Themes',
    //                 'target' => '_self',
    //                 'icon_class' => 'voyager-paint-bucket',
    //                 'color' => null,
    //                 'parent_id' => null,
    //                 'order' => 5,
    //             ]));
    //             $this->ensurePermissionExist();

    //             // return redirect()->back();
    //         }
    //     }
    // }

    // public function addModuleMenuItem(Menu $menu)
    // {
    //     if ($menu->name == 'admin') {
    //         $url = route('modules.all', [], false);
    //         $menuItem = $menu->items->where('url', $url)->first();
    //         if (is_null($menuItem)) {
    //             $menu->items->add(MenuItem::create([
    //                 'menu_id' => $menu->id,
    //                 'url' => $url,
    //                 'title' => 'Modules',
    //                 'target' => '_self',
    //                 'icon_class' => 'voyager-plug',
    //                 'color' => null,
    //                 'parent_id' => 8,
    //                 'order' => 6,
    //             ]));
    //             $this->ensurePermissionExist();

    //             // return redirect()->back();
    //         }
    //     }
    // }

    protected function ensurePermissionExist()
    {
        $permission = Permission::firstOrNew([
            'key' => 'browse_themes',
            'table_name' => 'admin',
        ]);
        if (!$permission->exists) {
            $permission->save();
            $role = Role::where('name', 'super')->first();
            if (!is_null($role)) {
                $role->permissions()->attach($permission);
            }
        }
    }

    private function loadDynamicMiddleware($themes_folder, $theme){
        if (empty($theme)) {
            return;
        }
        $middleware_folder = $themes_folder . '/' . $theme->folder . '/middleware';
        if(file_exists( $middleware_folder )){
            $middleware_files = scandir($middleware_folder);
            foreach($middleware_files as $middleware){
                if($middleware != '.' && $middleware != '..'){
                    include($middleware_folder . '/' . $middleware);
                    $middleware_classname = 'Themes\\Middleware\\' . str_replace('.php', '', $middleware);
                    if(class_exists($middleware_classname)){
                        // Dynamically Load The Middleware
                        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware($middleware_classname);
                    }
                }
            }
        }
    }

    private function addThemesTable()
    {
        if (!Schema::hasTable('themes')) {
            Schema::create('themes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('folder', 191)->unique();
                $table->boolean('active')->default(false);
                $table->string('version')->default('');
                $table->timestamps();
            });

            Schema::create('theme_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('theme_id')->unsigned()->index();
                $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
                $table->string('key');
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }
    }

    private function addWebsitesTable()
    {
        if (!Schema::hasTable('websites')) {
            Schema::create('websites', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('uuid');
                $table->string('managed_by_database_connection')
                ->nullable()
                ->comment('References the database connection key in your database.php');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    private function addHostnamesTable()
    {
        if (!Schema::hasTable('hostnames')) {
            Schema::create('hostnames', function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->string('fqdn')->unique();
                $table->string('redirect_to')->nullable();
                $table->boolean('force_https')->default(false);
                $table->timestamp('under_maintenance_since')->nullable();
                $table->bigInteger('website_id')->unsigned()->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('website_id')->references('id')->on('websites')->onDelete('set null');
            });
        }
    }

    function rescue(callable $callback, $rescue = null)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            report($e);
            return value($rescue);
        }
    }

}
