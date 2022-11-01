<?php

namespace Ensue\NicoSystem;

use Illuminate\Support\Facades\File;
use Ensue\NicoSystem\Validation\ValidationServiceProvider;

/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/29/2016
 * Time: 10:17 PM
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->registerModulesProviders();

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(ValidationServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->app->register(CommandServiceProvider::class);
        }

        include_once(__DIR__ . '/Foundation/helpers.php');
    }

    /**
     * Get providers from modules
     */
    protected function registerModulesProviders()
    {

        $modulePath = $this->app['config']->get('nicoSystem.module');
        if (!$modulePath) {
            return;
        }
        $modules = array_map('basename', File::directories(app_path($modulePath)));

        foreach ($modules as $module) {
            //register serviceProvider class in the folder if it exists
            if (File::exists(app_path($modulePath . '/' . $module . '/ServiceProvider.php')) && class_exists('\App\\' . $modulePath . '\\' . $module . '\ServiceProvider')) {
                $this->app->register('\App\\' . $modulePath . '\\' . $module . '\ServiceProvider');
            }

            //look into Providers folder
            $directoryPath = "$modulePath/$module/Providers";
            $providersDirectory = app_path($directoryPath);
            if (File::exists($providersDirectory) && File::isDirectory($providersDirectory)) {
                $files = array_map('basename', File::glob($providersDirectory . '/*.php'));
                //remove the extension
                foreach ($files as &$file) {
                    $file = explode('.', $file)[0];
                    $class = '\App\\' . $directoryPath . '\\' . $file;
                    if (class_exists($class)) {
                        $this->app->register($class);
                    }
                }
            }


        }

    }

    public function boot()
    {

    }
}
