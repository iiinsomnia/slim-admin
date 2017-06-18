<?php
// DIC configuration
$container = $app->getContainer();

$container['MenuDao'] = function ($c) {
    $dao = new App\Dao\MySQL\MenuDao($c);

    return $dao;
};

$container['RoleDao'] = function ($c) {
    $dao = new App\Dao\MySQL\RoleDao($c);

    return $dao;
};

$container['AssignDao'] = function ($c) {
    $dao = new App\Dao\MySQL\AssignDao($c);

    return $dao;
};

$container['UserDao'] = function ($c) {
    $dao = new App\Dao\MySQL\UserDao($c);

    return $dao;
};
?>