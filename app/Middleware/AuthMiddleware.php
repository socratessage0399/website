<?php

namespace Application\Middleware;

class AuthMiddleware extends Middleware
{


    public function __invoke($request, $response, $next)
    {
        if (!$this->container->auth->check())
        {
            return $this->container->response->withRedirect($this->container->router->pathFor('auth.signin'));
        }
        $response = $next($request, $response);
        return $response;
    }

}