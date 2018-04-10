<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Facades;

use Illuminate\Support\Facades\Route;
use Newestapps\Uploads\Http\Middlewares\UploadsMiddleware;

class Uploads
{

    public static function routes()
    {
        return Route::prefix('api/uploads/')
            ->middleware(['auth:api', UploadsMiddleware::class])
            ->as('nw-uploads::')
            ->group(__DIR__.'/../../routes/nw-uploads.php');
    }

}