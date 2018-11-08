<?php

namespace App\Model;


/**
 * Class Session
 * Реализует некоторые удобства на сайте
 *
 * @package App\Model
 */
class Session
{
    /**
     * Добавляет в Сессию сообщение об успехе или ошибке.
     * Данной сообщение в последствии затирается из страницы при помощи JS
     *
     * @param string $message
     * @param bool $success
     */
    public static function addSessionStatus(string $message, bool $success = false): void
    {
        $_SESSION['status'] = [
            'success' => $success,
            'message' => $message
        ];
    }

    /**
     * Добавляет метку об авторизации пользователя (админа)
     */
    public static function addAdminMarker():void
    {
        $_SESSION['adminMarker'] = true;
    }

    /**
     * Удаляет метку авторизации.
     * Пользователь считается не авторизованным
     */
    public static function delAdminMarker():void
    {
        if (isset($_SESSION['adminMarker'])) {
            unset($_SESSION['adminMarker']);

        }
    }
}