<?php

namespace Ensue\Snap;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class SnapProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/snap.php', 'snap');
        $this->mergeConfigFrom(__DIR__ . '/../config/fileupload.php', 'fileupload');
        $this->registerModulesProviders();
        $this->app->register(SnapRouteProvider::class);
        $this->app->register(SnapViewProvider::class);
        $this->app->register(ValidationServiceProvider::class);
        if ($this->app->runningInConsole()) {
            $this->app->register(SnapCommandProvider::class);
        }
        include_once(__DIR__ . '/Foundation/helpers.php');
    }

    /**
     * Get providers from modules
     */
    protected function registerModulesProviders(): void
    {
        $modulePath = $this->app['config']->get('snap.module');
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

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'snap');
        $this->publishes([
            __DIR__ . '/../config/snap.php' => config_path('snap.php'),
            __DIR__ . '/../config/fileupload.php' => config_path('fileupload.php'),
            __DIR__ . '/../lang' => $this->app->langPath(),
        ], 'snap');
    }
}

