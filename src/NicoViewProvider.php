<?php

namespace Ensue\NicoSystem;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class NicoViewProvider extends BaseServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->registerViewAndTranslations();
    }

    protected function registerViewAndTranslations(): null|array
    {
        $file = new Filesystem();
        $modulePath = $this->app['config']->get('nicoSystem.module');
        if (!$modulePath || !is_dir(app_path($modulePath))) {
            return null;
        }
        $modules = File::directories(app_path($modulePath));
        foreach ($modules as $module) {
            $view = $module . "/Views";
            $trans = $module . "/Translations";

            if ($file->exists($view)) {
                $this->loadViewsFrom($view, basename($module));
            }
            if ($file->exists($trans)) {
                $this->loadTranslationsFrom($trans, basename($module));
            }
        }
    }

}
