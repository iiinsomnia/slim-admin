<?php
namespace App\Middlewares;

use App\Helpers\SessionHelper;
use Psr\Container\ContainerInterface;

class AuthMiddleware
{
    protected $success = true;
    protected $msg = 'success';

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
        $this->auth();

        if (!$this->success) {
            $uri = $this->container->router->pathFor('login');

            if ($request->isXhr()) {
                return $response->withJson([
                    'success' => $this->success,
                    'msg'     => $this->msg,
                    'data'    => ['next' => $uri],
                ], 200);
            } else {
                return $response->withStatus(302)->withHeader('Location', $uri);
            }
        }

        $response = $next($request, $response);

        return $response;
    }

    // 验证登录
    protected function auth()
    {
        if (!SessionHelper::has('user')) {
            $this->success = false;
            $this->msg = '登录已过期';

            return;
        }

        $loginInfo = json_decode(SessionHelper::get('user'), true);

        if (empty($loginInfo)) {
            $this->success = false;
            $this->msg = '登录已过期';

            return;
        }

        if ($loginInfo['duration'] != 0) {
            $duration = time() - strtotime($loginInfo['last_login_time']);

            if ($duration >= $loginInfo['duration']) {
                SessionHelper::destroy();

                $this->success = false;
                $this->msg = '登录已过期';

                return;
            }
        }

        return;
    }
}