<?php
namespace App\Middlewares;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class RbacMiddleware
{
    protected $container;

    function __construct(ContainerInterface $c) {
        $this->container = $c;
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $route = $request->getAttribute('route');

        // 路由权限验证
        $pass = $this->auth($route->getName());

        if (!$pass) {
            if ($request->isXhr()) {
                return $response->withJson([
                    'success' => false,
                    'msg'     => '权限不足',
                    'data'    => [],
                ], 200);
            }

            return $this->container->view->render($response, 'error/error.twig', [
                'title' => 403,
                'msg'   => '权限不足',
            ]);
        }

        $response = $next($request, $response);

        return $response;
    }

    // 验证路由权限
    protected function auth($route)
    {
        $rbac = json_decode(SessionHelper::get('rbac'), true);

        if (empty($rbac)) {
            return false;
        }

        if (!in_array($route, $rbac['route'])) {
            return false;
        }

        return true;
    }
}