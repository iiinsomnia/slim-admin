<?php
namespace App\Cache;

use Psr\Container\ContainerInterface;

class AuthCache
{
    private $_redis;

    private $_cacheCookieKey = "auth:cookie";
    private $_cacheSessionKey = "auth:session";
    private $_cacheDeviceKey = "auth:device";

    function __construct(ContainerInterface $c)
    {
        $this->_redis = $c->get('redis');
    }

    // 设置登录验证用户信息缓存，token相当于sessionID
    public function setAuthData($phone, $uuid, $token, $data)
    {
        $this->_redis->hset($this->_cacheDeviceKey, $phone, $uuid);
        $this->_redis->hset($this->_cacheCookieKey, $uuid, $token);
        $this->_redis->hset($this->_cacheSessionKey, $token, json_encode($data));

        return;
    }

    // 获取登录验证后的用户信息
    public function getAuthData($uuid)
    {
        $token = $this->_redis->hget($this->_cacheCookieKey, $uuid);

        if (empty($token)) {
            return [];
        }

        $data = $this->_redis->hget($this->_cacheSessionKey, $token);

        if (empty($data)) {
            return [];
        }

        $loginInfo = json_decode($data, true);

        return $loginInfo;
    }

    // 获取用户登录的唯一token
    public function getAuthToken($uuid)
    {
        $token = $this->_redis->hget($this->_cacheCookieKey, $uuid);

        return $token;
    }

    // 用于注销上一台设备登录验证的信息
    public function delAuthDataByPhone($phone)
    {
        $uuid = $this->_redis->hget($this->_cacheDeviceKey, $phone);
        $token = $this->_redis->hget($this->_cacheCookieKey, $uuid);

        $this->_redis->hdel($this->_cacheSessionKey, $token);
        $this->_redis->hdel($this->_cacheCookieKey, $uuid);

        return;
    }

    // 用于注销本次登录验证信息
    public function delAuthDataByUuid($uuid)
    {
        $token = $this->_redis->hget($this->_cacheCookieKey, $uuid);

        $this->_redis->hdel($this->_cacheSessionKey, $token);
        $this->_redis->hdel($this->_cacheCookieKey, $uuid);

        return;
    }
}
?>