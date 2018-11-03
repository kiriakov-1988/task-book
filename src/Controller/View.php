<?php

namespace App\Controller;


class View
{
    public function getIndexPage(): bool
    {
        $this->generate('index_view.php');
        return true;
    }

    private function generate(string $content_view, array $data = null): void
    {
        include '../view/template_view.php';
    }
}