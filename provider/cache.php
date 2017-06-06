<?php
// DIC configuration
$container = $app->getContainer();

$container['AuthCache'] = function ($c) {
    $cache = new \App\Cache\AuthCache($c);

    return $cache;
};

$container['ArticleCache'] = function ($c) {
    $cache = new \App\Cache\ArticleCache($c);

    return $cache;
};
?>