<?php
// Данные сообщения генерируются в классе App\Model\Session

if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];

    if ($status['success']) {
        echo "<p class=\"p3 text-success\" id=\"message\">{$status['message']}</p>";
    } else {
        echo "<p class=\"p3 text-danger\" id=\"message\">{$status['message']}</p>";
    }

    $_SESSION['status'] = null;
}
