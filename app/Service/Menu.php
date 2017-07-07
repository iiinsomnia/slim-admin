<?php
namespace App\Service;

use Psr\Container\ContainerInterface;

class Menu extends Service
{
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }

    public function rules()
    {
        return [
            'name' => [
                'label'    => '菜单名称',
                'required' => true,
            ],
        ];
    }

    // 菜单列表分页
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
                    case 'route':
                        $where[] = 'route = ?';
                        $binds[] = $v;
                        break;
                    case 'pid':
                        $where[] = 'pid = ?';
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

        $result = $this->container->MenuDao->getByPagination($where, $binds, $offset, $size);

        $result['pages'] = ceil($result['count'] / $size);

        return $result;
    }

    // 添加菜单
    public function add($input)
    {
        $id = $this->container->MenuDao->addNewRecord($input);

        return $id;
    }

    // 获取菜单详情
    public function getMenuDetail($id)
    {
        $data = $this->container->MenuDao->getById($id);

        return $data;
    }

    // 编辑菜单
    public function edit($id, $input)
    {
        $rows = $this->container->MenuDao->updateById($id, $input);

        return $rows;
    }

    // 删除菜单
    public function delete($id)
    {
        $rows = $this->container->MenuDao->deleteById($id);

        return $rows;
    }

    // 是否含有子菜单
    public function hasSubMenus($id)
    {
        $data = $this->container->MenuDao->getByPid($id);

        return !empty($data) ? true : false;
    }

    // 获取所有父级菜单
    public function getPMenus()
    {
        $menus = $this->container->MenuDao->getAll();

        $data = [];

        foreach ($menus as $v) {
            if (empty($v['route'])) {
                $data[] = $v;
            }
        }

        return $data;
    }
}
?>