<?php

return [
    'name'      => env('APP_NAME'),
    'env'       => env('APP_ENV'),
    'debug'     => env('APP_DEBUG'),
    'url'       => env('APP_URL'),
    'timezone'  => 'UTC',
    'key'       => env('APP_KEY'),
    'cipher'    => 'AES-256-CBC',
    'log'       => env('APP_LOG'),
    'log_level' => env('APP_LOG_LEVEL'),
    'providers' => [

        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        // Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        Eagle\Providers\RouteServiceProvider::class,

    ],
];
