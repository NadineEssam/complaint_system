<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['request']->server->set('SCRIPT_NAME', '/call_center/index.php');
    
    	\Illuminate\Support\Facades\URL::forceRootUrl('http://msme-app-i.sfd.egypt.dom/call_center');
    	\Illuminate\Support\Facades\URL::forceScheme('http');
    }
}
