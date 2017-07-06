<?php
namespace App\Controllers;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class Controller
{
    protected $container;
    protected $viewDir;
    protected $commonData;

    function __construct(ContainerInterface $c, $dir = false) {
        $this->container = $c;
        $this->viewDir = $dir;

        $this->__initCommonData();
    }

    // 当前用户是否为游客身份
    protected function isGuest()
    {
        $identity = SessionHelper::get('identity');

        if (empty($identity)) {
            return true;
        }

        return false;
    }

    // 试图渲染
    protected function render($response, $viewName, $args = [])
    {
        $path = '';

        if ($this->viewDir !== false) {
            $path = sprintf("%s/%s.twig", $this->viewDir, $viewName);
        } else {
            $path = sprintf("%s.twig", $viewName);
        }

        $data = array_merge($this->commonData, $args);

        return $this->container->view->render($response, $path, $data);
    }

    // 试图403渲染
    protected function render403($response, $msg)
    {
        return $this->container->view->render($response, 'error/error.twig', [
            'title' => 403,
            'msg'   => $msg,
        ]);
    }

    // 试图404渲染
    protected function render404($response, $msg)
    {
        return $this->container->view->render($response, 'error/error.twig', [
            'title' => 404,
            'msg'   => $msg,
        ]);
    }

    // 试图500渲染
    protected function render500($response, $msg)
    {
        return $this->container->view->render($response, 'error/error.twig', [
            'title' => 500,
            'msg'   => $msg,
        ]);
    }

    // AJAX试图渲染
    protected function renderAjax($viewName, $args = [])
    {
        $path = '';

        if ($this->viewDir !== false) {
            $path = sprintf("%s/%s.twig", $this->viewDir, $viewName);
        } else {
            $path = sprintf("%s.twig", $viewName);
        }

        $data = array_merge($this->commonData, $args);

        return $this->container->view->fetch($path, $data);
    }

    // 跳转
    protected function redirect($response, $route, $args = [], $query = [])
    {
        $uri = $this->container->router->pathFor($route, $args, $query);

        return $response->withStatus(302)->withHeader('Location', $uri);
    }

    // JSON
    protected function json($response, $success = true, $msg = null, $resp = [], $redirect = []) {
        $result = [
            'success' => $success,
            'msg'     => $msg,
            'data'    => $resp,
        ];

        if (!empty($redirect)) {
            $route = $redirect[0];
            $args = isset($redirect['args']) ? $redirect['args'] : [];
            $query = isset($redirect['query']) ? $redirect['query'] : [];

            $uri = $this->container->router->pathFor($route, $args, $query);

            $result['redirect'] = $uri;
        }

        return $response->withJson($result, 200);
    }

    private function __initCommonData()
    {
        $this->commonData = [
            'version'  => env('APP_VERSION', '1.0.0'),
            'identity' => json_decode(SessionHelper::get('identity'), true),
            'rbac'     => json_decode(SessionHelper::get('rbac'), true),
        ];

        return;
    }
}
?>