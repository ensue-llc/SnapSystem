<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 12:46 AM
 */

namespace Ensue\NicoSystem;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ViewServiceProvider extends BaseServiceProvider
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
        if (!$modulePath) {
            return [];
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
