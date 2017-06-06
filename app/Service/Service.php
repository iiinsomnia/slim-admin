<?php
namespace App\Service;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class Service
{
    private $_userID = 0;
    private $_userInfo = [];
    private $_userAuth = [];

    protected $container;

    function __construct(ContainerInterface $c)
    {
        $this->_initLoginInfo();
        $this->_initLoginAuth();

        $this->container = $c;
    }

    /**
     * 用户登录
     * @param  array   $data     用户信息
     * @param  integer $duration 有效时间，秒
     */
    protected function signIn($data, $duration = 0)
    {
        $loginIP = $_SERVER['REMOTE_ADDR'];
        $loginTime = date('Y-m-d H:i:s');

        $result = $this->container->UserDao->updateById($data['id'], [
                'last_login_ip'   => $loginIP,
                'last_login_time' => $loginTime,
            ]);

        if ($result === false) {
            return false;
        }

        $data['last_login_ip'] = $loginIP;
        $data['last_login_time'] = $loginTime;
        $data['duration'] = $duration;

        $loginInfo = json_encode($data);

        SessionHelper::set('user', $loginInfo);

        return true;
    }

    /**
     * 用户注销
     */
    protected function signOut()
    {
        SessionHelper::destroy();
    }

    private function _initLoginInfo()
    {
        $loginInfo = SessionHelper::get('user');
        $userInfo = json_decode($loginInfo, true);

        if (!empty($userInfo)) {
            $this->_userID = $userInfo['id'];
            $this->_userInfo = $userInfo;
        }

        return;
    }

    private function _initLoginAuth()
    {
        $loginAuth = SessionHelper::get('auth');
        $userAuth = json_decode($loginInfo, true);

        if (!empty($userAuth)) {
            $this->_userAuth = $userAuth;
        }

        return $userAuth;
    }
}
?>