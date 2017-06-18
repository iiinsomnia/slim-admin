<?php
namespace App\Middlewares;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class AuthMiddleware
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
        // 登录验证
        $pass = $this->auth();

        if (!$pass) {
            $uri = $this->container->router->pathFor('login');

            if ($request->isXhr()) {
                return $response->withJson([
                    'success'  => false,
                    'msg'      => '登录已过期',
                    'data'     => [],
                    'redirect' => $uri,
                ], 200);
            }

            return $response->withStatus(302)->withHeader('Location', $uri);
        }

        $response = $next($request, $response);

        return $response;
    }

    // 验证登录
    protected function auth()
    {
        $user = json_decode(SessionHelper::get('user'), true);

        if (empty($user)) {
            return false;
        }

        $profile = $user['profile'];

        if ($profile['duration'] != 0) {
            $duration = time() - strtotime($profile['last_login_time']);

            if ($duration >= $profile['duration']) {
                SessionHelper::destroy();

                return false;
            }
        }

        return true;
    }
}