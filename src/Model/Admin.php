<?php

namespace App\Model;


class Admin
{
    private $login = 'admin';

    private $password = '123';

    public function authorizeAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ( isset($_POST['user']) && !empty($_POST['user']) && ($_POST['user'] == $this->login)
                && isset($_POST['pass']) && !empty($_POST['pass']) && ($_POST['pass'] == $this->password) ) {

                Session::addAdminMarker();

                header('Location: /');
            } else {

                Session::addSessionStatus('Неверный логин/пароль');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

        } else {
            Session::addSessionStatus('К маршруту "Авторизоваться" нельзя напрямую обращаться!');
            header('Location: /log-in');
        }

        return true;
    }

    public function logOutAdmin()
    {
        Session::delAdminMarker();

        header('Location: /');

        return true;
    }

}