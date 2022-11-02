<?php

namespace Ensue\NicoSystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class NicoRouteProvider extends BaseProvider
{
    /**
     * @var array
     */
    protected array $modules = [];

    /**
     * RouteServiceProvider constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->init();
    }

    protected function init(): void
    {
        $modulePath = $this->app['config']->get('nicosystem.module');
        if (!$modulePath || !is_dir(app_path($modulePath))) {
            return;
        }
        $modules = array_map('basename', File::directories(app_path($modulePath)));

        foreach ($modules as $module) {
            //look into Providers folder
            $directoryPath = "$modulePath/$module/Routes";

            $routePathFromApp = app_path($directoryPath);

            if (File::exists($routePathFromApp) && File::isDirectory($routePathFromApp)) {

                $this->modules[] = (object)[
                    'routePath' => $routePathFromApp,
                    'namespace' => "\\App\\$modulePath\\$module\\Controllers",
                    'hasApi' => File::exists($routePathFromApp . '/api.php'),
                    'hasWeb' => File::exists($routePathFromApp . '/web.php'),
                ];
            }

        }
    }

    public function boot(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        parent::boot();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        foreach ($this->modules as $module) {
            if ($module->hasApi) {
                Route::group([
                    'middleware' => 'api',
                    'namespace' => $module->namespace,
                    'prefix' => 'api',
                ], function ($router) use ($module) {
                    require($module->routePath . "/api.php");
                });
            }
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        foreach ($this->modules as $module) {
            if ($module->hasWeb) {
                Route::group([
                    'middleware' => 'web',
                    'namespace' => $module->namespace,
                ], static function ($router) use ($module) {
                    require($module->routePath . "/web.php");
                });
            }
        }
    }
}
