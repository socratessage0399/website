<?php

require_once 'bootstrap/autoload.php';

session_start();

$app = new Slim\App(require_once 'app/config.php');
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

date_default_timezone_set($container['settings']['timezone']);
setlocale(LC_ALL, $container['settings']['locale']);

$container['auth'] = function($container) {
    return new Application\Auth\Authentication($container);
};

$container['db'] = function($container) use ($capsule) {
    return $capsule;
};

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/resources/views', [
        'cache' => false,
        'debug' => true
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user'  => $container->auth->user()
    ]);
    $view->getEnvironment()->addGlobal('baseURL', $container->request->getUri()->getBaseUrl());
    $view->getEnvironment()->addGlobal('uri', $container->request->getUri()->getPath());
    $view->getEnvironment()->addGlobal('old', $container->request->getParsedBody());
    return $view;
};

$container['csrf'] = function($container){
    return new \Slim\Csrf\Guard;
};

$container['baseURL'] = function($container){
    return $container->request->getUri()->getBaseUrl();
};

$container['baseDIR'] = function () {
    return __DIR__ . '\\';
};

$app->add(new \Application\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

require_once 'app/routes.php';

$app->run();