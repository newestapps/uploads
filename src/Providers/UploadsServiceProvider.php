<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Newestapps\Uploads\Http\Middleware\UploadsMiddleware;

class UploadsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/nw-uploads.php', 'nw-uploads');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->app->make(Factory::class)->load(__DIR__.'/../../database/factories.php');

        $this->publishes([
            __DIR__.'/../../config/nw-uploads.php' => config_path('nw-uploads.php'),
        ], 'config');

        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::prefix('uploads/')
            ->middleware([UploadsMiddleware::class])
            ->as('nw-uploads::')
            ->namespace('Newestapps\Uploads\Http\Controllers')
            ->group(__DIR__.'/../../routes/nw-uploads.php');
    }


}