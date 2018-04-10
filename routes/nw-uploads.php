<?php

use Illuminate\Support\Facades\Route;

Route::post('{strategy}', '\\Newestapps\\Uploads\\Http\\Controllers\\UploadsController@upload')->name('upload');