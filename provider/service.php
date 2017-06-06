<?php
// DIC configuration
$container = $app->getContainer();

$container['Auth'] = function ($c) {
    $service = new \App\Service\Auth($c);

    return $service;
};
?>