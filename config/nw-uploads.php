<?php

return [

    'strategies' => [
        'default' => [
            'class' => \Newestapps\Uploads\Strategies\DefaultUploadStrategy::class,
            'driver' => 'local', // (same options as your laravel installation)
            'path' => storage_path('/app/public/nw-uploads'),
            'prefix' => '',
            'suffix' => '',
            'mimes' => [],
//            's3-bucket' => 'newestapps'
        ],
    ],

];