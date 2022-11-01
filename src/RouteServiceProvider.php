<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 12:00 AM
 */

namespace Ensue\NicoSystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends \App\Providers\RouteServiceProvider
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

    protected function init()
    {
        $modulePath = $this->app['config']->get('nicoSystem.module');
        if (!$modulePath) {
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

    public function register()
    {
        parent::register();
    }

    public function boot()
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
    protected function mapApiRoutes()
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
    protected function mapWebRoutes()
    {
        foreach ($this->modules as $module) {
            if ($module->hasWeb) {
                Route::group([
                    'middleware' => 'web',
                    'namespace' => $module->namespace,
                ], function ($router) use ($module) {
                    require($module->routePath . "/web.php");
                });
            }

        }
    }
}
