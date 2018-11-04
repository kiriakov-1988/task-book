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
}