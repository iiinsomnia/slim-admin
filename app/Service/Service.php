<?php
namespace App\Service;

use App\Helpers\ArrayHelper;
use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class Service
{
    protected $uid = 0;
    protected $user = [];

    protected $container;

    function __construct(ContainerInterface $c)
    {
        $this->_initProfile();

        $this->container = $c;
    }

    /**
     * 用户登录
     * @param  array   $profile  用户信息
     * @param  integer $duration 有效时间(秒)
     */
    protected function signIn($profile, $duration = 0)
    {
        $loginIP = $_SERVER['REMOTE_ADDR'];
        $loginTime = date('Y-m-d H:i:s');

        $result = $this->container->UserDao->updateById($profile['id'], [
                'last_login_ip'   => $loginIP,
                'last_login_time' => $loginTime,
            ]);

        if ($result === false) {
            return false;
        }

        // 基本信息
        $role = $this->container->RoleDao->getById($profile['role']);

        $profile['rolename'] = !empty($role) ? $role['name'] : '';
        $profile['last_login_ip'] = $loginIP;
        $profile['last_login_time'] = $loginTime;
        $profile['duration'] = $duration;

        // 菜单和路由
        $assign = $this->_getUserAssign($profile['role']);

        $loginInfo = [
            'profile' => $profile,
            'nav'     => $assign['nav'],
            'route'   => $assign['route'],
        ];

        SessionHelper::set('user', json_encode($loginInfo));

        return true;
    }

    /**
     * 用户注销
     */
    protected function signOut()
    {
        SessionHelper::destroy();
    }

    // 用户登录信息
    private function _initProfile()
    {
        $user = json_decode(SessionHelper::get('user'), true);

        if (!empty($user)) {
            $this->uid = $user['profile']['id'];
            $this->user = $user['profile'];
        }

        return;
    }

    // 设置用户分配路由权限
    private function _getUserAssign($roleId)
    {
        $menus = $this->container->MenuDao->getAll();
        $assigns = $this->container->AssignDao->getByRoleId($roleId);

        $routes = !empty($assigns) ? ArrayHelper::getColumn($assigns, 'route') : [];

        $nav = !empty($routes) ? $this->_tree($menus, $routes) : [];

        return [
            'nav'   => $nav,
            'route' => $routes,
        ];
    }

    // 获取菜单树形结构
    private function _tree($menus, $routes) {
        $tree = [];
        $nav = [];

        foreach ($menus as $v) {
            $pid = $v['pid'];
            $list = !empty($tree[$pid]) ? $tree[$pid] : [];
            array_push($list, $v);
            $tree[$pid] = $list;
        }

        foreach ($tree[0] as $v) {
            if (!empty($tree[$v['id']])) {
                $branches = $tree[$v['id']];
                $this->_branch($branches, $tree, $routes);

                if (!empty($branches)) {
                    $v['branches'] = array_values($branches);
                    $nav[] = $v;
                }
            } else {
                if (in_array($v['route'], $routes)) {
                    $nav[] = $v;
                }
            }
        }

        return $nav;
    }

    // 菜单分支
    private function _branch(&$data, $tree, $routes) {
        foreach ($data as $k => &$v) {
            if (!empty($tree[$v['id']])) {
                $branches = $tree[$v['id']];
                $this->_branch($branches, $tree, $routes);

                if (empty($branches)) {
                    unset($data[$k]);
                } else {
                    $v['branches'] = array_values($branches);
                }
            } else {
                if (!in_array($v['route'], $routes)) {
                    unset($data[$k]);
                }
            }
        }
    }
}
?>