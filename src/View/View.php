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

    private function generate(string $content_view, array $data = null): void
    {
        include '../templates/template_view.php';
    }
}