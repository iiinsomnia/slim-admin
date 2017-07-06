<?php
namespace App\Service;

use App\Helpers\ArrayHelper;
use Psr\Container\ContainerInterface;

class Assign extends Service
{
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }

    public function assign($roleId, $input)
    {
        $data = [];

        if (!empty($input['route'])) {
            foreach ($input['route'] as $v) {
                $data[] = [
                    'role_id' => $roleId,
                    'route'   => $v,
                ];
            }
        }

        $result = $this->container->AssignDao->batchAddWithDelete($roleId, $data);

        return $result;
    }

    public function getRoleAssigns($roleId)
    {
        $data = $this->container->AssignDao->getByRoleId($roleId);

        if (!empty($data)) {
            $data = ArrayHelper::getColumn($data, 'route');
        }

        return $data;
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