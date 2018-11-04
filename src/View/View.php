<?php

namespace App\View;


use App\Model\DB;
use App\Model\Session;

class View
{
    /**
     * Максимальное количество задач на странице .
     */
    const MAX_PER_PAGE = 3;

    public function getIndexPage(string $page = 'page-1'): ?bool
    {
        $page = substr($page, 5);

        try {
            $db = new DB();
            $result = $db->getTasks((int)$page);

            if (!isset($result['success'])) {
                // Запрос на несуществующую страницу (в пагинации)
                return null;
            }

            if ($result['success']) {
                $data = [
                    'listOfTasks' => $result['listOfTasks'],
                    'pagination'  => $result['pagination']
                ];
            } else {
                Session::addSessionStatus('При загрузке данных из БД произошла ошибка в БД');
                $data = [
                    'listOfTasks' => '',
                ];
            }

        } catch (\PDOException $e) {
            // Тут наверно не совсем желательно выводить текст ошибки из Исключения, а может только инфо-сообщение.
            // Но такая ошибка восновном возникнет при развертывании приложения - и будет как раз в помощь.
            Session::addSessionStatus('При загрузке данных из БД произошла PDO ошибка - ' . $e->getMessage());
            $data = [
                'listOfTasks' => '',
            ];
        }

        $this->generate('index_view.php', $data);
        return true;
    }

    public function editTask(int $id): ?bool
    {
        $data = [];

        try {
            $db = new DB();
            $taskData = $db->getTask($id);

            $data = [
                'taskData' => $taskData,
            ];
        } catch (\PDOException $e) {
            // Тут наверно не совсем желательно выводить текст ошибки из Исключения, а может только инфо-сообщение.
            // Но такая ошибка восновном возникнет при развертывании приложения - и будет как раз в помощь.
            Session::addSessionStatus('При загрузке данных о задаче №' . $id . ' из БД произошла PDO ошибка - ' . $e->getMessage());
        }

        if ($data['taskData']) {
            $this->generate('edit_task_view.php', $data);
            return true;
        }

        return null;
    }

    public function logIn():bool
    {
        $this->generate('auth_form_view.php');
        return true;
    }

    /**
     * Отображает страницу, информирующую о 404 ошибке.
     * Данная страница выводится при обращении к странице,
     * маршрут которой не задан/не предусмотрен в Роутере
     */
    public function showError(): void
    {
        $this->generate('404-error_view.php');
    }

    private function generate(string $content_view, array $data = null): void
    {
        include '../templates/template_view.php';
    }
}