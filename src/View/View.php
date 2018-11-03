<?php

namespace App\View;


class View
{
    public function getIndexPage(): bool
    {
        $this->generate('index_view.php');
        return true;
    }

    private function generate(string $content_view, array $data = null): void
    {
        include '../templates/template_view.php';
    }
}