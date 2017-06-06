<?php
namespace App\Service;

use App\Dao\MySQL\UserDao;
use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as v;

class Auth extends Service
{
    function __construct(ContainerInterface $di)
    {
        $this->container = $di;
    }

    public function registerRules()
    {
        return [
            'username' => [
                'label'    => '用户名',
                'required' => true,
            ],
            'phone' => [
                'label'    => '手机号',
                'required' => true,
            ],
            'email' => [
                'label'    => '邮箱',
                'valids'   => [
                    v::email(),
                ],
                'required' => true,
            ],
            'password' => [
                'label'    => '密码',
                'required' => true,
            ],
            'password_repeat' => [
                'label'    => '确认密码',
                'required' => true,
            ],
        ];
    }

    public function loginRules()
    {
        return [
            'username' => [
                'label'    => '用户名',
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

    // 处理注册请求
    public function register($input, &$success, &$msg)
    {
        $errMsg = $this->validateRegisterData($input);

        if (!empty($errMsg)) {
            $success = false;
            $msg = $errMsg;

            return;
        }

        $salt = $this->_generateSalt();

        $data = [
            'username' => $input['username'],
            'phone'    => $input['phone'],
            'email'    => $input['email'],
            'password' => md5($input['password'] . $salt),
            'salt'     => $salt,
            'role'     => 0,
            'reg_ip'   => $_SERVER['REMOTE_ADDR'],
            'reg_time' => date('Y-m-d H:i:s'),
        ];

        $result = $this->container->UserDao->add($data);

        if (!$result) {
            $success = false;
            $msg = '注册失败';

            return;
        }

        $msg = '注册成功';

        return;
    }

    // 处理登录请求
    public function login($input, &$success, &$msg)
    {
        if (strtolower($input['captcha']) != SessionHelper::get('captcha')) {
            $success = false;
            $msg = '验证码错误';

            return;
        }

        $dbData = $this->container->UserDao->getByName($input['username']);

        if (empty($dbData)) {
            $success = false;
            $msg = '用户不存在';

            return;
        }

        if (md5($input['password'] . $dbData['salt']) != $dbData['password']) {
            $success = false;
            $msg = '用户名或密码错误';

            return;
        }

        $result = $this->signIn($dbData);

        if (!$result) {
            $success = false;
            $msg = '登录失败';

            return;
        }

        $msg = '登录成功';

        return;
    }

    // 处理注销请求
    public function logout()
    {
        $this->signOut();
    }

    // 验证注册数据
    protected function validateRegisterData($input)
    {
        if ($input['password_repeat'] != $input['password']) {
            return '密码确认有误';
        }

        $dbData = $this->container->UserDao->getByName($input['username']);

        if (!empty($dbData)) {
            return '该用户名已被注册';
        }

        $dbData = $this->container->UserDao->getByPhone($input['phone']);

        if (!empty($dbData)) {
            return '该手机号已被注册';
        }

        $dbData = $this->container->UserDao->getByEmail($input['email']);

        if (!empty($dbData)) {
            return '该邮箱已被注册';
        }

        return null;
    }

    /**
     * 生成随机加密盐
     * @return string
     */
    private function _generateSalt()
    {
        $salt = '';
        $pattern = 'abcdef!ghijklm@nopqrst#uvwxyz$12345%67890^ABCDEFGH&IJKLMNOP*QRSTUVWXYZ';

        for($i = 0; $i < 16; $i++){
            $c = $pattern[mt_rand(0 , 69)];
            $salt .= $c;
        }

        return $salt;
    }
}
?>