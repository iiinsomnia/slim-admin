# slim-api

SlimFramework整合Laravel/Database、MongoDB、Predis，用于后台管理或web开发，支持CLI-Command

支持邮件发送运行时错误

### 使用：

```sh
# get the framework and dependency libraries
composer update
```

```sh
# cli-command
php cli greet IIInsomnia

# output
Hello IIInsomnia
```

```sh
# display help message
php cli greet -h
```

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

### 参考
* [Slim](http://www.slimphp.net/)
* [Laravel/Database](https://laravel.com/docs/5.4/database)
* [MongoDB](https://docs.mongodb.com/php-library/master/tutorial/)
* [Predis](https://packagist.org/packages/predis/predis)
* [CLI-Command](http://symfony.com/doc/current/components/console.html)


