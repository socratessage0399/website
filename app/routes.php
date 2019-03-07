<?php

use Application\Models\User;

# Home
$app->get('/', 'Application\Controllers\HomeController:index')->setName('home');

# View profile
$app->get('/users/@{username}', 'Application\COntrollers\UserController:show')->setName('user.profile');

$app->group('', function() {

    $this->get('/auth/logout', function($request, $response) {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    })->setName('auth.logout');

    $this->map(['GET', 'POST'], '/account', 'Application\COntrollers\UserController:settings')->setName('user.settings');

})->add(new \Application\Middleware\AuthMiddleware($container));

$app->group('', function() {

    # Sign up
    $this->map(['GET', 'POST'], '/auth/signup', 'Application\Controllers\Auth\SignupController:index')->setName('auth.signup');

    # Sign in
    $this->map(['GET', 'POST'], '/auth/signin', 'Application\Controllers\Auth\SigninController:index')->setName('auth.signin');


})->add(new \Application\Middleware\GuestMiddleware($container));