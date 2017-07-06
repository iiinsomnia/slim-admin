<?php
// DIC configuration
$container = $app->getContainer();

// Illuminate/database
$container['db'] = function($c) {
    $connections = $c->get('settings')['db'];

    $capsule = new \Illuminate\Database\Capsule\Manager;

    foreach ($connections as $name => $config) {
        $capsule->addConnection($config, $name);
    }

    $capsule->setAsGlobal();

    return $capsule;
};

// MongoDB
$container['mongo'] = function($c) {
    $config = $c->get('settings')['mongo'];

    $dsn = sprintf('mongodb://%s:%s', $config['host'], $config['port']);

    if (!empty($config['username'])) {
        $dsn = sprintf('mongodb://%s:%s@%s:%s', $config['username'], $config['password'], $config['host'], $config['port']);
    }

    $client = new \MongoDB\Client($dsn);

    return $client;
};

// Predis
$container['redis'] = function($c) {
    $config = $c->get('settings')['redis'];

    $client = new \Predis\Client([
        'scheme'   => 'tcp',
        'host'     => $config['host'],
        'port'     => $config['port'],
        'password' => $config['password'],
        'database' => $config['database'],
    ], ['prefix' => $config['prefix']]);

    return $client;
};

// Monolog
$container['logger'] = function($c) {
    $config = $c->get('settings')['logger'];

    $logger = new Monolog\Logger($config['name']);
    $logger->pushHandler(new Monolog\Handler\StreamHandler($config['path'], $config['level']));

    return $logger;
};

// Providers
$providers = array_merge(
    require __DIR__ . '/../providers/dao.php',
    require __DIR__ . '/../providers/cache.php',
    require __DIR__ . '/../providers/command.php'
);

foreach ($providers as $alias => $obj) {
    $container[$alias] = function($c) use ($obj) {
        $instance = new $obj($c);

        return $instance;
    };
}
?>