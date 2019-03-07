<?php

namespace Application\Controllers\Auth;


use Application\Controllers\Controller;
use Application\Validation\SigninValidation;

class SigninController extends Controller
{

    public function index($request, $response)
    {
        $errors = [];
        if ($request->isPost())
        {
            $validation = new SigninValidation();
            $validation->validate($request);
            $errors = $validation->getErrors();

            if (empty($errors))
            {
                if ($this->auth->authorize($request->getParam('email'), $request->getParam('password')))
                {
                    return $response->withRedirect($this->router->pathFor('home'));
                } else {
                    $errors['auth'] = "Эти учетные данные не соответствуют нашим записям.";
                }
            }

        }

        return $this->view->render($response, 'auth/signin.twig', [
            'errors' => $errors
        ]);
    }

}