<?php

namespace App\View;


use App\Model\DB;
use App\Model\Session;

class View
{
    public function getIndexPage(): bool
    {
        try {
            $db = new DB();
            $listOfTasks = $db->getTasks();

            $data = [
                'listOfTasks' => $listOfTasks,
            ];
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