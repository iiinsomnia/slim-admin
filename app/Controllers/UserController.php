<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Helpers\ValidateHelper;

class UserController extends Controller
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'user');
    }

    public function index($request, $response, $args)
    {
        $query = $request->getQueryParams();

        $list = $this->container->User->pagination($query);

        if (!$request->isXhr()) {
            $roles = $this->container->Role->getAllRoles();

            return $this->render($response, 'index', [
                'roles' => $roles,
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
            $roles = $this->container->Role->getAllRoles();

            return $this->render($response, 'add', ['roles' => $roles]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->User->profileRules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $error = $this->container->User->validateUnique($input);

        if (!empty($error)) {
            return $this->json($response, false, $error);
        }

        $id = $this->container->User->add($input);

        if ($id === false) {
            return $this->json($response, false, '添加失败');
        }

        return $this->json($response, true, '添加成功', [], ['user.index']);
    }

    public function edit($request, $response, $args)
    {
        if ($request->isGet()) {
            $roles = $this->container->Role->getAllRoles();
            $data = $this->container->User->getUserDetail($args['id']);

            return $this->render($response, 'edit', [
                'roles' => $roles,
                'data'  => $data,
            ]);
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->User->profileRules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $rows = $this->container->User->edit($args['id'], $input);

        if ($rows === false) {
            return $this->json($response, false, '编辑失败');
        }

        return $this->json($response, true, '编辑成功', [], ['user.index']);
    }

    public function reset($request, $response, $args)
    {
        $rows = $this->container->User->resetPassword($args['id']);

        if ($rows === false) {
            return $this->json($response, false, '密码重置失败');
        }

        return $this->json($response, true, '密码重置成功');
    }

    public function delete($request, $response, $args)
    {
        $rows = $this->container->User->delete($args['id']);

        if ($rows === false) {
            return $this->json($response, false, '删除失败');
        }

        return $this->json($response, true, '删除成功', [], ['user.index']);
    }

    public function profile($request, $response, $args)
    {
        return $this->render($response, 'profile');
    }

    public function password($request, $response, $args)
    {
        if ($request->isGet()) {
            return $this->render($response, 'password');
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->User->passwordRules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        if ($input['password'] != $input['password_repeat']) {
            return $this->json($response, false, '密码确认错误');
        }

        $rows = $this->container->User->changePassword($input['password']);

        if ($rows === false) {
            return $this->json($response, false, '修改失败');
        }

        $this->container->Auth->logout();

        return $this->json($response, true, '修改成功', [], ['login']);
    }
}
?>