<?php
namespace App\Controllers;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class Controller
{
    protected $success = true;
    protected $msg = 'success';
    protected $resp = [];

    protected $container;
    protected $viewDir;
    protected $loginData;

    function __construct(ContainerInterface $c, $dir = false) {
        $this->container = $c;
        $this->viewDir = $dir;

        $this->loginData = [
            'user' => json_decode(SessionHelper::get('user'), true),
            'auth' => json_decode(SessionHelper::get('auth'), true),
        ];
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

        $data = array_merge($this->loginData, $args);

        return $this->container->view->render($response, $path, $data);
    }

    // 跳转
    protected function redirect($response, $route)
    {
        $uri = $this->container->router->pathFor($route);

        return $response->withStatus(302)->withHeader('Location', $uri);
    }

    // JSON
    protected function json($response, $next = null) {
        $result = [
            'success' => $this->success,
            'msg'     => $this->msg,
            'data'    => $this->resp,
        ];

        if ($this->success && !empty($next)) {
            $uri = $this->container->router->pathFor($next);
            $result['data']['next'] = $uri;
        }

        return $response->withJson($result, 200);
    }
}
?>