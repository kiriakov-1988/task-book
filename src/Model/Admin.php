<?php

namespace App\Model;


/**
 * Class Admin
 * @package App\Model
 */
class Admin
{
    /**
     * Дефолтный юзер
     *
     * @var string
     */
    private $login = 'admin';

    /**
     * Дефолтный пароль.
     * В реальном проекте, пароль необходимо хранить в виде хеша
     *
     * @var string
     */
    private $password = '123';

    /**
     * Служит для выполнения базовой авторизации админа.
     * Для реализации этой функциональности используется Сессия как простой способ
     *
     *
     * @return bool
     */
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

    /**
     * Служит для "выхода" завторизированого админа
     *
     * @return bool
     */
    public function logOutAdmin()
    {
        Session::delAdminMarker();

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        return true;
    }

}