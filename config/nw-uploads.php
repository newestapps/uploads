<?php

return [

    'strategies' => [

        'default' => [
            'class' => \Newestapps\Uploads\Strategies\DefaultUploadStrategy::class,
            'driver' => 'local', // (same options as your laravel installation)
            'path' => 'public/nw-uploads',
        ],

    ],

];