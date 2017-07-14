<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Helpers\ValidateHelper;

class RoleController extends Controller
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'role');
    }

    public function index($request, $response, $args)
    {
        $query = $request->getQueryParams();

        $list = $this->container->Role->pagination($query);

        if (!$request->isXhr()) {
            return $this->render($response, 'index', ['list' => $list]);
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
            return $this->render($response, 'add');
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Role->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $id = $this->container->Role->add($input);

        if ($id === false) {
            return $this->json($response, false, '添加失败');
        }

        return $this->json($response, true, '添加成功', null, ['role.index']);
    }

    public function edit($request, $response, $args)
    {
        if ($request->isGet()) {
            $data = $this->container->Role->getRoleDetail($args['id']);

            return $this->render($response, 'edit', ['data' => $data]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Role->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $rows = $this->container->Role->edit($args['id'], $input);

        if ($rows === false) {
            return $this->json($response, false, '编辑失败');
        }

        return $this->json($response, true, '编辑成功', null, ['role.index']);
    }

    public function delete($request, $response, $args)
    {
        $rows = $this->container->Role->delete($args['id']);

        if ($rows === false) {
            return $this->json($response, false, '删除失败');
        }

        return $this->json($response, true, '删除成功', null, ['role.index']);
    }
}
?>