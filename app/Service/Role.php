<?php
namespace App\Service;

use Psr\Container\ContainerInterface;

class Role extends Service
{
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }

    public function rules()
    {
        return [
            'name' => [
                'label'    => '角色名称',
                'required' => true,
            ],
        ];
    }

    // 角色列表分页
    public function pagination($query = [], $size = 10)
    {
        $where = [];
        $binds = [];

        foreach ($query as $k => $v) {
            if(trim($v) !== ''){
                switch ($k) {
                    case 'name':
                        $keywords = sprintf('%%%s%%', $v);
                        $where[] = 'name LIKE ?';
                        $binds[] = $keywords;
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

        $result = $this->container->RoleDao->getByPage($where, $binds, $offset, $size);

        $result['pages'] = ceil($result['count'] / $size);

        return $result;
    }

    // 添加角色
    public function add($input)
    {
        $id = $this->container->RoleDao->addNewRecord($input);

        return $id;
    }

    // 获取角色详情
    public function getRoleDetail($id)
    {
        $data = $this->container->RoleDao->getById($id);

        return $data;
    }

    // 编辑角色
    public function edit($id, $input)
    {
        $rows = $this->container->RoleDao->updateById($id, $input);

        return $rows;
    }

    // 删除角色
    public function delete($id)
    {
        $rows = $this->container->RoleDao->deleteById($id);

        return $rows;
    }

    // 获取所有角色
    public function getAllRoles()
    {
        $data = $this->container->RoleDao->getAll();

        return $data;
    }
}
?>