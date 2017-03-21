<?php
namespace Webmachine\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider {
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('form.php')
        ], 'config');
        
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'form'
        );        
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {        
        return \App::bind('form', function(){
            return new Form();
        });
    }
}