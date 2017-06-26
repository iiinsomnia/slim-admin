<?php
// DIC configuration
$container = $app->getContainer();

$container['DemoCache'] = function($c) {
    $cache = new App\Dao\MySQL\DemoCache($c);

    return $cache;
};
?>