<?php
// DIC configuration
$container = $app->getContainer();

// slim/twig-view
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../views', [
        'cache' => false,
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

// Illuminate/database
$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];

    $capsule = new \Illuminate\Database\Capsule\Manager;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $settings['host'],
        'database'  => $settings['database'],
        'username'  => $settings['username'],
        'password'  => $settings['password'],
        'charset'   => $settings['charset'],
        'collation' => $settings['collation'],
        'prefix'    => $settings['prefix'],
    ]);

    $capsule->setAsGlobal();

    return $capsule;
};

// MongoDB
$container['mongo'] = function ($c) {
    $settings = $c->get('settings')['mongo'];

    $dsn = sprintf('mongodb://%s:%s', $settings['host'], $settings['port']);

    if (!empty($settings['username'])) {
        $dsn = sprintf('mongodb://%s:%s@%s:%s', $settings['username'], $settings['password'], $settings['host'], $settings['port']);
    }

    $client = new \MongoDB\Client($dsn);

    return $client;
};

// Predis
$container['redis'] = function ($c) {
    $settings = $c->get('settings')['redis'];

    $client = new \Predis\Client([
        'scheme'   => 'tcp',
        'host'     => $settings['host'],
        'port'     => $settings['port'],
        'password' => $settings['password'],
        'database' => $settings['database'],
    ], ['prefix' => $settings['prefix']]);

    return $client;
};

// Flash
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];

    $logger = new Monolog\Logger($settings['name']);
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

// Captcha
$container['captcha'] = function ($c) {
    $settings = $c->get('settings')['captcha'];

    $builder = new \Gregwar\Captcha\CaptchaBuilder;
    $builder->build($settings['width'], $settings['height']);

    return $builder;
};

if (!env('APP_DEBUG', false)) {
    // 404NotFound
    $container['notFoundHandler'] = function ($c) {
        return function ($request, $response) use ($c) {
            if ($request->isXhr()) {
                return $response->withJson([
                    'success' => false,
                    'msg'     => 'page not found',
                ], 200);
            }

            return $c->view->render($response, 'error/error.twig', [
                'title' => 404,
                'msg'   => 'page not found',
            ]);
        };
    };

    // 405NotAllowed
    $container['notAllowedHandler'] = function ($c) {
        return function ($request, $response, $methods) use ($c) {
            if ($request->isXhr()) {
                return $response->withJson([
                    'success' => false,
                    'msg'     => 'method not allowed',
                ], 200);
            }

            return $c->view->render($response, 'error/error.twig', [
                'title' => 405,
                'msg'   => 'method not allowed',
            ]);
        };
    };

    // ErrorHandler
    $container['errorHandler'] = function ($c) {
        return function ($request, $response, $error) use ($c) {
            $c['logger']->error(null, [
                    'message' => $error->getMessage(),
                    'file'    => $error->getFile(),
                    'line'    => $error->getLine(),
                ]);

            if (env('ERROR_MAIL', false)) {
                \App\Helpers\MailerHelper::sendErrorMail($error);
            }

            if ($request->isXhr()) {
                return $response->withJson([
                    'success' => false,
                    'msg'     => 'server internal error',
                ], 200);
            }

            return $c->view->render($response, 'error/error.twig', [
                'title' => 500,
                'msg'   => 'server internal error',
            ]);
        };
    };

    // PHPErrorHandler
    $container['phpErrorHandler'] = function ($c) {
        return function ($request, $response, $error) use ($c) {
            $c['logger']->error(null, [
                    'message' => $error->getMessage(),
                    'file'    => $error->getFile(),
                    'line'    => $error->getLine(),
                ]);

            if (env('ERROR_MAIL', false)) {
                \App\Helpers\MailerHelper::sendErrorMail($error);
            }

            if ($request->isXhr()) {
                return $response->withJson([
                    'success' => false,
                    'msg'     => 'server internal error',
                ], 200);
            }

            return $c->view->render($response, 'error/error.twig', [
                'title' => 500,
                'msg'   => 'server internal error',
            ]);
        };
    };
}
?>