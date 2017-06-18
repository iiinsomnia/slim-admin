<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Helpers\ValidateHelper;

class AssignController extends Controller
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'assign');
    }

    public function assign($request, $response, $args)
    {
        if ($request->isGet()) {
            $role = $this->container->Role->getRoleDetail($args['roleId']);

            if (empty($role)) {
                return $this->json($response, false, '角色不存在');
            }

            $routes = $this->container->Auth->getAllRoutes();
            $assigns = $this->container->Assign->getRoleAssigns($args['roleId']);

            return $this->render($response, 'assign', [
                'roleId'   => $args['roleId'],
                'roleName' => $role['name'],
                'routes'   => $routes,
                'assigns'  => $assigns,
            ]);
        }

        $input = $request->getParsedBody();

        $result = $this->container->Assign->assign($args['roleId'], $input);

        if (!$result) {
            return $this->json($response, false, '分配失败');
        }

        return $this->json($response, true, '分配成功', [], ['role.index']);
    }
}
?>