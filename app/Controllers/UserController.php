<?php

namespace Application\Controllers;

use Application\Models\User;

class UserController extends Controller
{

    public function show($request, $response, $args)
    {
        $username = strip_tags(trim($args['username']));
        $user = User::where('username', $username)->first();

        if (!$user) return $response->withRedirect($this->router->pathFor('home'));

        return $this->view->render($response, 'users/profile.twig', [
            'user' => $user
        ]);

    }

    public function settings($request, $response)
    {
        $errors = [];

        if ($request->isPost())
        {
            if (!empty($_FILES['image']['name']))
            {
                $ext = ['jpg', 'png', 'jpeg', 'gif'];
                $size = 3200000;
                $file = [
                    'name' => pathinfo($_FILES['image']['name'], PATHINFO_FILENAME),
                    'tmp_name' => $_FILES['image']['tmp_name'],
                    'extension' => pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION),
                    'size' => $_FILES['image']['size'],
                    'error' => $_FILES['image']['error']
                ];

                if (!in_array($file['extension'], $ext)) $errors['image'][] = "Поле <b>Изображение</b> должно быть изображением.";
                if ($file['size'] > $size) $errors['image'][] = "Размер файла в поле <b>Изображение</b> должен быть не менее 3200 Килобайт(а).";

                if (empty($errors))
                {
                    if ($this->auth->user()->image != 'default.png') {
                        unlink($this->baseDIR . 'public/images/users/' . $this->auth->user()->image);
                    }

                    $newImageName = md5($file['name']) . '.' . $file['extension'];
                    if (move_uploaded_file($file['tmp_name'], $this->baseDIR . 'public/images/users/' . $newImageName)) {
                        $user = $this->auth->user();
                        $user->image = $newImageName;
                        $user->save();
                    }
                }

            }

            $user = $this->auth->user();
            $user->first_name = !empty($request->getParam('first_name')) ? $request->getParam('first_name') : $user->first_name;
            $user->last_name = !empty($request->getParam('last_name')) ? $request->getParam('last_name') : $user->last_name;
            $user->save();

            return $response->withRedirect($this->router->pathFor('user.settings'));
        }

        $this->view->render($response, 'users/settings.twig', ['errors' => $errors]);
    }

}