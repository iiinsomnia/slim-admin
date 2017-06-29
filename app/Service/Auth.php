<?php
namespace App\Service;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as v;

class Auth extends Service
{
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }

    public function rules()
    {
        return [
            'account' => [
                'label'    => '登录账号',
                'required' => true,
            ],
            'password' => [
                'label'    => '密码',
                'required' => true,
            ],
            'captcha' => [
                'label'    => '验证码',
                'required' => true,
            ],
        ];
    }

    // 用户登录
    public function login($input)
    {
        if (strtolower($input['captcha']) != SessionHelper::get('captcha')) {
            return [
                'success' => false,
                'msg'     => '验证码错误',
            ];
        }

        $user = $this->container->UserDao->getByAccount($input['account']);

        if (empty($user)) {
            return [
                'success' => false,
                'msg'     => '帐号不存在',
            ];
        }

        if (md5($input['password'] . $user['salt']) != $user['password']) {
            return [
                'success' => false,
                'msg'     => '帐号或密码错误',
            ];
        }

        $result = $this->signIn($user, 12 * 3600);

        if (!$result) {
            return [
                'success' => false,
                'msg'     => '登录失败',
            ];
        }

        return [
            'success' => true,
            'msg'     => '登录成功',
        ];
    }

    // 注销用户
    public function logout()
    {
        $this->signOut();
    }

    // 获取所有定义的路由
    public function getAllRoutes()
    {
        $routes = $this->container->router->getRoutes();
        $data = [];

        foreach ($routes as $v) {
            $data[] = $v->getName();
        }

        return $data;
    }
}
?>