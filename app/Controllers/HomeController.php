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

    public function actionHome($request, $response, $args)
    {
        return $this->render($response, 'index');
    }

    public function actionRegister($request, $response, $args)
    {
        if ($request->isGet()) {
            return $this->render($response, 'register');
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Auth->registerRules());

        if (!empty($errors)) {
            $this->success = false;
            $this->msg = $errors;

            return $this->json($response);;
        }

        $this->container->Auth->register($input, $this->success, $this->msg);

        return $this->json($response, 'login');
    }

    public function actionLogin($request, $response, $args)
    {
        if ($request->isGet()) {
            return $this->render($response, 'login');
        }

        $input = $request->getParsedBody();

        $errors = ValidateHelper::validate($input, $this->container->Auth->loginRules());

        if (!empty($errors)) {
            $this->success = false;
            $this->msg = $errors;

            return $this->json($response);;
        }

        $this->container->Auth->login($input, $this->success, $this->msg);

        return $this->json($response, 'home');
    }

    public function actionLogout($request, $response, $args)
    {
        $this->container->Auth->logout();

        return $this->redirect($response, 'login');
    }

    public function actionCaptcha($request, $response, $args)
    {
        $response = $response->withHeader('Content-Type', 'image/jpeg');

        $captcha = $this->container->captcha->getPhrase();
        SessionHelper::set('captcha', $captcha);

        $this->container->captcha->output();

        return $response;
    }
}
?>