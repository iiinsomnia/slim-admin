<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Helpers\ValidateHelper;

class MenuController extends Controller
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'menu');
    }

    public function index($request, $response, $args)
    {
        $query = $request->getQueryParams();

        $list = $this->container->Menu->pagination($query);

        if (!$request->isXhr()) {
            $routes = $this->container->Auth->getAllRoutes();
            $pmenus = $this->container->Menu->getPMenus();

            return $this->render($response, 'index', [
                'routes' => $routes,
                'pmenus' => $pmenus,
                'list'   => $list,
            ]);
        }

        $html = $this->renderAjax('pagination', ['data' => $list['data']]);

        $resp = [
            'count' => $list['count'],
            'pages' => $list['pages'],
            'html'  => $html,
        ];

        return $this->json($response, true, 'success', $resp);
    }

    public function add($request, $response, $args)
    {
        if ($request->isGet()) {
            $colors = $this->container->params['colors'];
            $routes = $this->container->Auth->getAllRoutes();

            return $this->render($response, 'add', [
                'colors' => $colors,
                'routes' => $routes,
            ]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Menu->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $id = $this->container->Menu->add($input);

        if ($id === false) {
            return $this->json($response, false, '添加失败');
        }

        return $this->json($response, true, '添加成功', [], ['menu.index']);
    }

    public function submenu($request, $response, $args)
    {
        if ($request->isGet()) {
            $pmenu = $this->container->Menu->getMenuDetail($args['pid']);

            if (empty($pmenu)) {
                return $this->json($response, false, '所属菜单不存在');
            }

            $routes = $this->container->Auth->getAllRoutes();

            return $this->render($response, 'submenu', [
                'pid'    => $args['pid'],
                'pname'  => $pmenu['name'],
                'routes' => $routes,
            ]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Menu->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $input['pid'] = $args['pid'];

        $id = $this->container->Menu->add($input);

        if ($id === false) {
            return $this->json($response, false, '添加失败');
        }

        return $this->json($response, true, '添加成功', [], ['menu.index']);
    }

    public function edit($request, $response, $args)
    {
        if ($request->isGet()) {
            $hasSubmenus = $this->container->Menu->hasSubMenus($args['id']);

            $colors = $this->container->params['colors'];
            $routes = !$hasSubmenus ? $this->container->Auth->getAllRoutes() : [];

            $data = $this->container->Menu->getMenuDetail($args['id']);

            return $this->render($response, 'edit', [
                'colors' => $colors,
                'routes' => $routes,
                'data'   => $data,
            ]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Menu->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $rows = $this->container->Menu->edit($args['id'], $input);

        if ($rows === false) {
            return $this->json($response, false, '编辑失败');
        }

        return $this->json($response, true, '编辑成功', [], ['menu.index']);
    }

    public function delete($request, $response, $args)
    {
        $hasSubmenus = $this->container->Menu->hasSubMenus($args['id']);

        if ($hasSubmenus) {
            return $this->json($response, false, '请先删除子菜单');
        }

        $rows = $this->container->Menu->delete($args['id']);

        if ($rows === false) {
            return $this->json($response, false, '删除失败');
        }

        return $this->json($response, true, '删除成功', [], ['menu.index']);
    }
}
?>