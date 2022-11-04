<?php

namespace Ensue\NicoSystem;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class NicoSystemProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nicosystem.php', 'nicosystem');
        $this->mergeConfigFrom(__DIR__ . '/../config/fileupload.php', 'fileupload');
        $this->registerModulesProviders();
        $this->app->register(NicoRouteProvider::class);
        $this->app->register(NicoViewProvider::class);
        $this->app->register(ValidationServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->app->register(NicoCommandProvider::class);
        }
        include_once(__DIR__ . '/Foundation/helpers.php');
    }

    /**
     * Get providers from modules
     */
    protected function registerModulesProviders(): void
    {
        $modulePath = $this->app['config']->get('nicosystem.module');
        if (!$modulePath || !is_dir(app_path($modulePath))) {
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

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'nicosystem');
        $this->publishes([
            __DIR__ . '/../config/nicosystem.php' => config_path('nicosystem.php'),
            __DIR__ . '/../config/fileupload.php' => config_path('fileupload.php'),
        ], 'nicosystem');
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath(),
        ], 'appconstant');
    }
}

