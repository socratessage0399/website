<?php

namespace Application\Controllers\Auth;

use Application\Models\User;
use Application\Controllers\Controller;
use Application\Validation\SignupValidation;


class SignupController extends Controller

{

    public function index($request, $response)
    {
        $errors = [];
        if ($request->isPost())
        {
            $validation = new SignupValidation();
            $validation->validate($request);
            $errors = $validation->getErrors();

            if (empty($errors))
            {
                User::create([
                    'username' => strip_tags($request->getParam('username')),
                    'email' => strip_tags($request->getParam('email')),
                    'password' => password_hash($request->getParam('password'), PASSWORD_BCRYPT, ['cost' => 10]),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                ]);
                if ($this->auth->authorize($request->getParam('email'), $request->getParam('password')))
                {
                    return $response->withRedirect($this->router->pathFor('home'));
                }
            }

        }

        return $this->view->render($response, 'auth/signup.twig', [
            'errors' => $errors
        ]);
    }

}
