<?php
namespace App\Controllers;

use App\Helpers\SessionHelper;
use App\Helpers\ValidateHelper;
use Psr\Container\ContainerInterface;

class HomeController extends Controller
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'home');
    }

    public function home($request, $response, $args)
    {
        return $this->render($response, 'index', [
            'serverOS'    => PHP_OS,
            'serverSoft'  => $_SERVER['SERVER_SOFTWARE'],
            'serverIP'    => $_SERVER['SERVER_ADDR'],
            'serverHost'  => $_SERVER['SERVER_NAME'],
            'PHPVersion'  => PHP_VERSION,
            'copyright'   => 'IIInsomnia 2017',
        ]);
    }

    public function register($request, $response, $args)
    {
        return $this->render($response, 'register');
    }

    public function login($request, $response, $args)
    {
        if ($request->isGet()) {
            if (!$this->isGuest()) {
                return $this->redirect($response, 'home');
            }

            return $this->render($response, 'login');
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Auth->rules());

        if (!empty($errors)) {
            return $this->json($response, false, $errors);
        }

        $result = $this->container->Auth->login($input);

        if (!$result['success']) {
            return $this->json($response, false, $result['msg']);
        }

        return $this->json($response, true, $result['msg'], [], ['home']);
    }

    public function logout($request, $response, $args)
    {
        $this->container->Auth->logout();

        return $this->redirect($response, 'login');
    }

    public function captcha($request, $response, $args)
    {
        $response = $response->withHeader('Content-Type', 'image/jpeg');

        $captcha = $this->container->captcha->getPhrase();
        SessionHelper::set('captcha', $captcha);

        $this->container->captcha->output();

        return $response;
    }
}
?>