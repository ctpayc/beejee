<?php

namespace App\Controllers;

use App\View\View;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends \Core\BaseController {

    /**
     * login page
     * @return void
     */
    public function login()
    {
        $flash = $this->getFlashMessages();

        View::render('login.html', [ 'flash' => $flash ]);
    }

    /**
     * try to auth user by creds
     * @return [type] [description]
     */
    public function authenticate()
    {
        $user = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ];

        if (!v::notBlank()->validate($user['email']) 
            || !v::notBlank()->validate($user['password'])) {
            $_SESSION["flash"] = ["type" => "danger", "message" => "Поля логин и пароль обязательны для заполнения!"];
            header('Location: ' . '/login');
            exit();
        } elseif (!User::auth($user)) {
            $_SESSION["flash"] = ["type" => "danger", "message" => "Неверный логин или пароль!"];
            header('Location: ' . '/login');
            exit();
        } else {
             if(!isset($_SESSION))
            {
                session_start();
            }
            $_SESSION["flash"] = ["type" => "success", "message" => "Вы успешно залогинены!"];
            $_SESSION["logged_in"] = true;

            header('Location: ' . '/');
            exit();
        }
    }

    public function logout()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        header('Location: ' . '/');
        exit();
    }

}