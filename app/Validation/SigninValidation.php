<?php

namespace Application\Validation;


class SigninValidation
{

    protected $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate($request)
    {
        if (!strlen($request->getParam('email'))) $this->errors['email'][] = "Поле <b>Электронная почта</b> обязательно для заполнения.";
        if (!strlen($request->getParam('password'))) $this->errors['password'][] = "Поле <b>Пароль</b> обязательно для заполнения.";
    }

}