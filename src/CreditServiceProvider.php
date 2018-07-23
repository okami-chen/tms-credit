<?php

namespace OkamiChen\TmsCredit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use OkamiChen\TmsCredit\Entity\Credit;
use OkamiChen\TmsCredit\Observer\CreditObserver;

class CreditServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        __NAMESPACE__.'\Console\Command\NotifyCommand',
    ];
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(dirname(__DIR__).'/resources/views', 'tms-credit');
        
        if ($this->app->runningInConsole()) {
            //$this->publishes([__DIR__.'/../config' => config_path()], 'tms-credit-config');
            $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/tms/credit')],'tms-credit-views');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'tms-credit-migrations');
        }
        
        $this->registerRoute();
        $this->registerObserver();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
    
    protected function registerRoute(){
        
        $attributes = [
            'prefix'     => config('admin.route.prefix'),
            'namespace'  => __NAMESPACE__.'\Controller',
            'middleware' => config('admin.route.middleware'),
        ];

        Route::group($attributes, function (Router $router) {
            $router->any('/module/credit/secret','SecretController@index')->name('tms.credit.secret');
            $router->any('/service/credit/search', 'SearchController@card')->name('tms.service.credit.search');
            $router->resource('/module/credit/default', 'CreditController',['as'=>'tms']);
            $router->resource('/module/credit/bill', 'BillController',['as'=>'tms']);
        });
    }
    
    /**
     * 
     */
    protected function registerObserver(){
        Credit::observe(CreditObserver::class);
    }
}
