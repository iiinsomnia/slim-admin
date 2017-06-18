<?php
namespace App\Service;

use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as v;

class User extends Service
{
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }

    public function profileRules()
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
            'role' => [
                'label'    => '角色',
                'valids'   => [
                    v::intval(),
                ],
                'required' => true,
            ],
        ];
    }

    public function passwordRules()
    {
        return [
            'password' => [
                'label'    => '新密码',
                'required' => true,
            ],
            'password_repeat' => [
                'label'    => '密码确认',
                'required' => true,
            ],
        ];
    }

    // 用户列表分页
    public function pagination($query = [], $size = 10)
    {
        $where = [];
        $binds = [];

        foreach ($query as $k => $v) {
            if(trim($v) !== ''){
                switch ($k) {
                    case 'username':
                        $where[] = 'a.username = ?';
                        $binds[] = $v;
                        break;
                    case 'role':
                        $where[] = 'a.role = ?';
                        $binds[] = $v;
                        break;
                }
            }
        }

        $where = implode(' AND ', $where);

        $page = isset($query['page']) ? intval($query['page']) : 1;

        $offset = ($page - 1) * $size;

        if ($offset < 0) {
            return [];
        }

        $result = $this->container->UserDao->getByPage($where, $binds, $offset, $size);

        $result['pages'] = ceil($result['count'] / $size);

        return $result;
    }

    // 添加用户
    public function add($input)
    {
        $salt = $this->generateSalt();

        $data = [
            'username' => $input['username'],
            'phone'    => $input['phone'],
            'email'    => $input['email'],
            'password' => md5(env('DEFAULT_PASS', '123') . $salt),
            'salt'     => $salt,
            'role'     => $input['role'],
        ];

        $id = $this->container->UserDao->addNewRecord($data);

        return $id;
    }

    // 获取用户详情
    public function getUserDetail($id)
    {
        $data = $this->container->UserDao->getById($id);

        return $data;
    }

    // 编辑用户
    public function edit($id, $input)
    {
        $rows = $this->container->UserDao->updateById($id, $input);

        return $rows;
    }

    // 修改密码
    public function changePassword($password)
    {
        $salt = $this->generateSalt();

        $data = [
            'password' => md5($password . $salt),
            'salt'     => $salt,
        ];

        $rows = $this->container->UserDao->updateById($this->uid, $data);

        return $rows;
    }

    // 重置用户密码
    public function resetPassword($id)
    {
        $salt = $this->generateSalt();

        $data = [
            'password' => md5(env('DEFAULT_PASS', '123') . $salt),
            'salt'     => $salt,
        ];

        $rows = $this->container->UserDao->updateById($id, $data);

        return $rows;
    }

    // 删除用户
    public function delete($id)
    {
        $rows = $this->container->UserDao->deleteById($id);

        return $rows;
    }

    // 验证用户唯一性
    public function validateUnique($input)
    {
        $user = $this->container->UserDao->getByName($input['username']);

        if (!empty($user)) {
            return '该用户名已被使用';
        }

        $user = $this->container->UserDao->getByPhone($input['phone']);

        if (!empty($user)) {
            return '该手机号已被使用';
        }

        $user = $this->container->UserDao->getByEmail($input['email']);

        if (!empty($user)) {
            return '该邮箱已被使用';
        }

        return null;
    }

    /**
     * 生成随机加密盐
     * @return string
     */
    protected function generateSalt()
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