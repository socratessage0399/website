<?php

namespace Application\Auth;

use Application\Models\User;

class Authentication
{

    protected $container;
    protected $token;

    public function __construct($container)
    {
        $this->container = $container;
        $this->token = $container['settings']['auth']['token'];
    }

    public function user()
    {
        if ($this->check()) return User::find($_SESSION[$this->token]);
    }

    public function check()
    {
        if (isset($_SESSION[$this->token]) && !empty($_SESSION[$this->token]))
        {
            return (boolean) User::find($_SESSION[$this->token])->count() > 0;
        }
        return false;
    }

    public function authorize($email, $password)
    {
        $user = User::where('email', $email)->first();
        if ($user)
        {
            if (password_verify($password, $user->password))
            {
                $_SESSION[$this->token] = $user->id;
                return true;
            }
        }
        return false;
    }

    public function logout()
    {
        unset($_SESSION[$this->token]);
    }

}