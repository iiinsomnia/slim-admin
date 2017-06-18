<?php
// DIC configuration
$container = $app->getContainer();

$container['Home'] = function ($c) {
    $service = new \App\Service\Home($c);

    return $service;
};

$container['Menu'] = function ($c) {
    $service = new \App\Service\Menu($c);

    return $service;
};

$container['Auth'] = function ($c) {
    $service = new \App\Service\Auth($c);

    return $service;
};

$container['Role'] = function ($c) {
    $service = new \App\Service\Role($c);

    return $service;
};

$container['Assign'] = function ($c) {
    $service = new \App\Service\Assign($c);

    return $service;
};

$container['User'] = function ($c) {
    $service = new \App\Service\User($c);

    return $service;
};
?>