<?php
namespace Webmachine\FormField;

use Illuminate\Support\ServiceProvider;

class FormFieldServiceProvider extends ServiceProvider {
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('formfield.php')
        ], 'config');
        
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'formfield'
        );        
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {        
        return \App::bind('form_field', function(){
            return new FormField();
        });
    }
}