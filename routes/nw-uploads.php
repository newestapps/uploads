<?php

use Illuminate\Support\Facades\Route;

Route::post('{strategy}', 'UploadsController@upload')->name('upload');