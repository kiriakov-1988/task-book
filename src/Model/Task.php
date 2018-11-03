<?php

namespace App\Model;


class Task
{
    public function addTask(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['user_name']) && !empty($_POST['user_name'])
                && isset($_POST['email']) && !empty($_POST['email'])
                && isset($_POST['task_text']) && !empty($_POST['task_text'])) {

                $data = [
                    'user_name' => trim(htmlspecialchars($_POST['user_name'])),
                    'email'     => trim(htmlspecialchars($_POST['email'])),
                    'task_text' => trim(htmlspecialchars($_POST['task_text'])),
                    'img'       => ''
                ]; // TODO trim внутри проверка

                if (isset($_FILES['userfile']) || !empty($_FILES['userfile'])) {

                    $file = new FileUploader();
                    $uploadStatus = $file->uploadFile();

                    if ($uploadStatus['success']) {
                        $data['img'] = $uploadStatus['uploadFileName'];
                    } else {
                        // TODO ошибка при загрузке
                    }
                }

                $db = new DB();

                if ($db->addTask($data)) {
                    // задача добавлена
                } else {
                    // ошибка при добавление задачи в ДБ
                }

            } else {

                // TODO + выполнить проверку с отключенной валидацией браузера
                // не все поля заполнены
                // так проверить длину текстов

            }
        } else {

            // TODO К маршруту "Добавить задачу" нельзя напрямую обращаться
            //

        }

        header('Location: /');

        // возвращаем true так как маршрут отработал (здесь и далее)
        return true;
    }
}