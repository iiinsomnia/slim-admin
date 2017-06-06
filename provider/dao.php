<?php
// DIC configuration
$container = $app->getContainer();

$container['UserDao'] = function ($c) {
    $dao = new App\Dao\MySQL\UserDao($c);

    return $dao;
};
?>