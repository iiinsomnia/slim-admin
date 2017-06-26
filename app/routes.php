<?php
// Routes
$app->get('/register', '\App\Controllers\HomeController:register')->setName('register');
$app->get('/captcha', '\App\Controllers\HomeController:captcha')->setName('captcha');
$app->map(['GET', 'POST'], '/login', '\App\Controllers\HomeController:login')->setName('login');
$app->get('/logout', '\App\Controllers\HomeController:logout')->setName('logout');

$app->group(null, function() {
    $this->get('/', '\App\Controllers\HomeController:home')->setName('home');
    // profile
    $this->get('/profile', '\App\Controllers\UserController:profile')->setName('profile');
    $this->map(['GET', 'POST'], '/password', '\App\Controllers\UserController:password')->setName('password');
    // menu
    $this->get('/menus', '\App\Controllers\MenuController:index')->setName('menu.index');
    $this->map(['GET', 'POST'], '/menus/add', '\App\Controllers\MenuController:add')->setName('menu.add');
    $this->map(['GET', 'POST'], '/menus/submenu/{pid:[0-9]+}', '\App\Controllers\MenuController:submenu')->setName('menu.submenu');
    $this->map(['GET', 'POST'], '/menus/edit/{id:[0-9]+}', '\App\Controllers\MenuController:edit')->setName('menu.edit');
    $this->get('/menus/delete/{id:[0-9]+}', '\App\Controllers\MenuController:delete')->setName('menu.delete');
    // auth
    $this->get('/auths', '\App\Controllers\AuthController:index')->setName('auth.index');
    $this->map(['GET', 'POST'], '/auths/add', '\App\Controllers\AuthController:add')->setName('auth.add');
    $this->map(['GET', 'POST'], '/auths/edit/{id:[0-9]+}', '\App\Controllers\AuthController:edit')->setName('auth.edit');
    $this->get('/auths/delete/{id:[0-9]+}', '\App\Controllers\AuthController:delete')->setName('auth.delete');
    // role
    $this->get('/roles', '\App\Controllers\RoleController:index')->setName('role.index');
    $this->map(['GET', 'POST'], '/roles/add', '\App\Controllers\RoleController:add')->setName('role.add');
    $this->map(['GET', 'POST'], '/roles/edit/{id:[0-9]+}', '\App\Controllers\RoleController:edit')->setName('role.edit');
    $this->get('/roles/delete/{id:[0-9]+}', '\App\Controllers\RoleController:delete')->setName('role.delete');
    $this->map(['GET', 'POST'], '/roles/{roleId:[0-9]+}/assign', '\App\Controllers\AssignController:assign')->setName('role.assign');
    // user
    $this->get('/users', '\App\Controllers\UserController:index')->setName('user.index');
    $this->map(['GET', 'POST'], '/users/add', '\App\Controllers\UserController:add')->setName('user.add');
    $this->map(['GET', 'POST'], '/users/edit/{id:[0-9]+}', '\App\Controllers\UserController:edit')->setName('user.edit');
    $this->get('/password/reset/{id:[0-9]+}', '\App\Controllers\UserController:reset')->setName('password.reset');
    $this->get('/users/delete/{id:[0-9]+}', '\App\Controllers\UserController:delete')->setName('user.delete');
})->add(\App\Middlewares\AuthMiddleware::class);
?>