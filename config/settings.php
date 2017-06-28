<?php
$params = require(__DIR__ . '/params.php');

return [
    'settings' => [
        'debug'                  => env('APP_DEBUG', false),
        'displayErrorDetails'    => env('APP_DEBUG', false), // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // db settings
        'db' => [
            'default' => [
                'driver'    => 'mysql',
                'host'      => env('DB_HOST', '127.0.0.1'),
                'port'      => env('DB_PORT', '3306'),
                'database'  => env('DB_DATABASE', 'test'),
                'username'  => env('DB_USERNAME', 'demo'),
                'password'  => env('DB_PASSWORD', ''),
                'charset'   => env('DB_CHARSET', 'utf8'),
                'collation' => env('DB_COLLATION', 'utf8_general_ci'),
                'prefix'    => env('DB_PREFIX', ''),
                'strict'    => false,
                'engine'    => null,
            ],
        ],

        // MongoDB settings
        'mongo' => [
            'host'     => env('MONGO_HOST', '127.0.0.1'),
            'port'     => env('MONGO_PORT', '27017'),
            'database' => env('MONGO_DATABASE', 'test'),
            'username' => env('MONGO_USERNAME', null),
            'password' => env('MONGO_PASSWORD', null),
            'prefix'   => env('MONGO_PREFIX', ''),
        ],

        // Predis settings
        'redis' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', 6379),
            'password' => env('REDIS_PASSWORD', null),
            'database' => env('REDIS_DATABASE', 0),
            'prefix'   => env('REDIS_PREFIX', ''),
        ],

        // Monolog settings
        'logger' => [
            'name'  => 'Monlog',
            'path'  => env('LOG_PATH', __DIR__ . '/../logs/') . date('Y-m-d') . '.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Captcha
        'captcha' => [
            'width'  => env('CAPTCHA_WIDTH', 120),
            'height' => env('CAPTCHA_HEIGHT', 34),
        ],
    ],
    'params' => $params,
];
?>