<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../bootstrap/env.php';

// Instantiate the app
$settings = require __DIR__ . '/../config/settings.php';
$app = new \Slim\App($settings);

// Set up bootstrap dependencies
require __DIR__ . '/../bootstrap/bootstrap.php';

// Set up dao & cache & service providers
require __DIR__ . '/../provider/dao.php';
require __DIR__ . '/../provider/cache.php';
require __DIR__ . '/../provider/service.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run app
$app->run();
?>