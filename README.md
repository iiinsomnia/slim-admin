# slim-admin

基于Slim3封装，用于后台管理和WEB开发

### 特点

* 使用Laravel/Database、MongoDB、Predis
* 支持CLI-Command，用于crontab
* 支持.env环境配置
* 使用依赖注入开发
* 支持邮件推送系统错误日志
* 内置登录和基于路由的RBAC模块

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

### 备注

* 服务器虚拟目录指向 `public` 目录
* 确保 `logs` 目录可写
* 导入 `demo.sql`
* 默认3个用户：admin/admin、slim/123、demo/123
* 新增用户默认密码为：123

### 参考

* [Slim](http://www.slimphp.net/)
* [Laravel/Database](https://laravel.com/docs/5.4/database)
* [MongoDB](https://docs.mongodb.com/php-library/master/tutorial/)
* [Predis](https://packagist.org/packages/predis/predis)
* [CLI-Command](http://symfony.com/doc/current/components/console.html)