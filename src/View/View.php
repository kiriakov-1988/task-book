<?php

namespace App\View;


use App\Model\DB;

class View
{
    public function getIndexPage(): bool
    {
        // TODO try/catch
        $db = new DB();
        $listOfTasks = $db->getTasks();

        $data = [
            'listOfTasks' => $listOfTasks,
        ];

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