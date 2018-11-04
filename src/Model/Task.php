<?php

namespace App\Model;


class Task
{
    public function addTask(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->checkAndGetPostValues();

            if ($result['checked']) {

                $data = $result['data'];

                if (isset($_FILES['userfile']) || !empty($_FILES['userfile'])) {

                    $file = new FileUploader();
                    $uploadStatus = $file->uploadFile();

                    if ($uploadStatus['success']) {
                        $data['img'] = $uploadStatus['uploadFileName'];
                    } else {
                        // Тут конечно при ошибке загрузки файла, можно было бы например просто это игнорировать
                        // и добавить задачу без картинки, так как они не обязательны
                        Session::addSessionStatus($uploadStatus['message']);

                        header('Location: /');
                        // маршрут отработал
                        return true;
                    }
                }

                try {
                    $db = new DB();

                    if ($db->addTask($data)) {

                        Session::addSessionStatus('Новая задача успешно добавлена добавлена!', true);

                    } else {
                        Session::addSessionStatus('Ошибка при добавлении задачи в ДБ!');
                    }
                } catch (\PDOException $e) {
                    Session::addSessionStatus('При добавлении задачи в БД произошла PDO ошибка - ' . $e->getMessage());
                }

            } else {
                Session::addSessionStatus($result[['message']]);
            }
        } else {
            Session::addSessionStatus('К маршруту "Добавить задачу" нельзя напрямую обращаться!');
        }

        header('Location: /');

        // возвращаем true так как маршрут отработал (здесь и далее)
        return true;
    }

    public function saveEditTask(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['id']) && !empty($_POST['id'])
                && isset($_POST['task_text']) && !empty($_POST['task_text'])
                && (mb_strlen($_POST['task_text'])) <=255) {

                $data = [
                    'id'        => (int)$_POST['id'],
                    'task_text' => htmlspecialchars(trim($_POST['task_text'])),
                    'status'    => ''
                ];

                if (isset($_POST['status'])) {
                    $data['status'] = true;
                }

                try {
                    $db = new DB();

                    if ($db->editTask($data)) {

                        Session::addSessionStatus('Задача успешно обновлена!', true);

                        header('Location: /');
                        return true;

                    } else {
                        Session::addSessionStatus('Ошибка при обновлении задачи в ДБ!');
                    }
                } catch (\PDOException $e) {
                    Session::addSessionStatus('При обновлении задачи в БД произошла PDO ошибка - ' . $e->getMessage());
                }

            } else {
                Session::addSessionStatus('Форма заполнена невалидными данными!');
            }

        } else {
            Session::addSessionStatus('К маршруту "Соранить задачу" нельзя напрямую обращаться!');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        return true;
    }

    private function checkAndGetPostValues():array
    {
        if (isset($_POST['user_name']) && !empty($_POST['user_name'])
            && isset($_POST['email']) && !empty($_POST['email'])
            && isset($_POST['task_text']) && !empty($_POST['task_text'])) {

            $data = [
                'user_name' => htmlspecialchars(trim($_POST['user_name'])),
                'email'     => htmlspecialchars(trim($_POST['email'])),
                'task_text' => htmlspecialchars(trim($_POST['task_text'])),
                'img'       => ''
            ];

            // Как дополнительную опцию можно реализовать сохранения переменных в сессию на случай ошибок
            // Но данный момент выполняется полная валидация данных в браузере
            // Ну а кто ее обходит - будет вносить заново данные ...

            $error = '';

            if (mb_strlen($data['user_name']) > 100) {
                $error .= 'Имя пользователя превышает 100 символов. ';
            }

            if (mb_strlen($data['email']) > 100) {
                $error .= 'E-mail пользователя превышает 100 символов. ';
            }

            if (mb_strlen($data['task_text']) > 255) {
                $error .= 'Текст задачи превышает 255 символов. ';
            }

            if (!$error) {
                return [
                    'checked' => true,
                    'data' => $data
                ];
            }

        } else {

            $error = '';

            if (!isset($_POST['user_name']) || empty($_POST['user_name'])) {
                $error .= 'Введите имя пользователя! ';
            }

            if (!isset($_POST['email']) || empty($_POST['email'])) {
                $error .= 'Введите e-mail пользователя! ';
            }

            if (!isset($_POST['task_text']) || empty($_POST['task_text'])) {
                $error .= 'Введите текст задачи! ';
            }
        }

        return [
            'checked' => false,
            'message' => $error
        ];
    }
}