<?php

namespace Application\Validation;

use Application\Models\User;

class SignupValidation
{

    protected $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate($request)
    {
        if (!strlen($request->getParam('username'))) $this->errors['username'][] = "Поле <b>Имя пользователя</b> обязательно для заполнения.";
        if (!preg_match('/^[A-Za-z0-9]+$/', $request->getParam('username'))) $this->errors['username'][] = "Поле <b>Имя пользователя</b> может содержать только буквы и цифры.";
        if (strlen($request->getParam('username')) < 3) $this->errors['username'][] = "Количество символов в поле <b>Имя пользователя</b> должно быть не менее 3.";
        if (strlen($request->getParam('username')) > 30) $this->errors['username'][] = "Количество символов в поле <b>Имя пользователя</b> не может превышать 30.";
        if (User::where('username', $request->getParam('username'))->count() > 0) $this->errors['username'][] = "Такое значение поля <b>Имя пользователя</b> уже существует.";

        if (!strlen($request->getParam('email'))) $this->errors['email'][] = "Поле <b>Электронная почта</b> обязательно для заполнения.";
        if (!filter_var($request->getParam('email'), FILTER_VALIDATE_EMAIL)) $this->errors['email'][] = "Поле <b>Электронная почта</b> должно быть действительным электронным адресом.";
        if (User::where('email', $request->getParam('email'))->count() > 0) $this->errors['username'][] = "Такое значение поля <b>Электронная почта/b> уже существует.";

        if (!strlen($request->getParam('password'))) $this->errors['password'][] = "Поле <b>Пароль</b> обязательно для заполнения.";
        if (strlen($request->getParam('password')) < 6) $this->errors['password'][] = "Количество символов в поле <b>Пароль</b> должно быть не менее 6.";
        if (strlen($request->getParam('password')) > 12) $this->errors['password'][] = "Количество символов в поле <b>Пароль</b> не может превышать 12.";
        if (!preg_match('/^[A-Za-z0-9]+$/', $request->getParam('password'))) $this->errors['password'][] = "Поле <b>Пароль</b> может содержать только буквы и цифры.";

        if (!strlen($request->getParam('repeat_password'))) $this->errors['repeat_password'][] = "Поле <b>Повторите пароль</b> обязательно для заполнения.";
        if ($request->getParam('repeat_password') != $request->getParam('password')) $this->errors['repeat_password'][] = "Поле <b>Повторите пароль</b> не совпадает с подтверждением.";

    }

}