<?php

namespace App\Model;


class Session
{
    public static function addSessionStatus(string $message, bool $success = false): void
    {
        $_SESSION['status'] = [
            'success' => $success,
            'message' => $message
        ];
    }

    public static function addAdminMarker():void
    {
        $_SESSION['adminMarker'] = true;
    }

    public static function delAdminMarker():void
    {
        if (isset($_SESSION['adminMarker'])) {
            unset($_SESSION['adminMarker']);

        }
    }
}