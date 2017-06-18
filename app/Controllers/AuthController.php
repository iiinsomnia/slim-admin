<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Helpers\ValidateHelper;

class AuthController extends Controller
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'auth');
    }

    public function index($request, $response, $args)
    {
        $query = $request->getQueryParams();

        $list = $this->container->Auth->pagination($query);

        if (!$request->isXhr()) {
            $menus = $this->container->Menu->getAllMenus();

            return $this->render($response, 'index', [
                'menus' => $menus,
                'list'  => $list,
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
            $menus = $this->container->Menu->getAllMenus();

            return $this->render($response, 'add', ['menus' => $menus]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Auth->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $id = $this->container->Auth->add($input);

        if ($id === false) {
            return $this->json($response, false, '添加失败');
        }

        return $this->json($response, true, '添加成功', [], ['auth.index']);
    }

    public function edit($request, $response, $args)
    {
        if ($request->isGet()) {
            $menus = $this->container->Menu->getAllMenus();
            $data = $this->container->Auth->getAuthDetail($args['id']);

            return $this->render($response, 'edit', [
                'menus' => $menus,
                'data'  => $data,
            ]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Auth->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $rows = $this->container->Auth->edit($args['id'], $input);

        if ($rows === false) {
            return $this->json($response, false, '编辑失败');
        }

        return $this->json($response, true, '编辑成功', [], ['auth.index']);
    }

    public function delete($request, $response, $args)
    {
        $rows = $this->container->Auth->delete($args['id']);

        if ($rows === false) {
            return $this->json($response, false, '删除失败');
        }

        return $this->json($response, true, '删除成功', [], ['auth.index']);
    }
}
?>