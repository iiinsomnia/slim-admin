<?php
// Routes
$app->map(['GET', 'POST'], '/register', '\App\Controllers\HomeController:actionRegister')->setName('register');
$app->get('/captcha', '\App\Controllers\HomeController:actionCaptcha')->setName('captcha');
$app->map(['GET', 'POST'], '/login', '\App\Controllers\HomeController:actionLogin')->setName('login');
$app->get('/logout', '\App\Controllers\HomeController:actionLogout')->setName('logout');

$app->group(null, function () {
    $this->get('/', '\App\Controllers\HomeController:actionHome')->setName('home');

    $this->get('/userinfo', '\App\Controllers\V1\UserController:actionView')->setName('profile');

    $this->get('/articles', '\App\Controllers\V1\ArticleController:actionList')->setName('article.index');
    $this->get('/articles/{id}', '\App\Controllers\V1\ArticleController:actionDetail')->setName('article.view');
    $this->post('/articles', '\App\Controllers\V1\ArticleController:actionAdd')->setName('article.add');
    $this->put('/articles/{id}', '\App\Controllers\V1\ArticleController:actionUpdate')->setName('article.update');
    $this->delete('/articles/{id}', '\App\Controllers\V1\ArticleController:actionDelete')->setName('article.delete');

    $this->get('/books', '\App\Controllers\V1\BookController:actionList')->setName('book.index');
    $this->get('/books/{id}', '\App\Controllers\V1\BookController:actionDetail')->setName('book.view');
    $this->post('/books', '\App\Controllers\V1\BookController:actionAdd')->setName('book.add');
    $this->put('/books/{id}', '\App\Controllers\V1\BookController:actionUpdate')->setName('book.update');
    $this->delete('/books/{id}', '\App\Controllers\V1\BookController:actionDelete')->setName('book.delete');
})->add(\App\Middlewares\AuthMiddleware::class);
?>