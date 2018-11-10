<?php

$total = $data['pagination']['totalPage'];

if ($total > 1) {

    // далее реализована простая пагинация, для показа возможного функционала
    $pagination = '';

// Проверяем нужна ли стрелка на первую страницу, при этом условия сортировки сбрасываются (просто так задумано)
    if ($page != 1){
        $pagination .= '<a href="/" title="Перейти на первую страницу, сбросив настройки сортировкой"><<</a> ';
    }

// Находим две ближайшие станицы с обоих краев, если они есть
    if($page - 2 > 0) {
        $pagination .= ' <a href="/page-'. ($page - 2)   . \App\Model\Session::getSorterInfoForPagination() .'">'. ($page - 2) .'</a> | ';
    }

    if($page - 1 > 0) {
        $pagination .= ' <a href="/page-'. ($page - 1)    . \App\Model\Session::getSorterInfoForPagination() .'">'. ($page - 1) .'</a> | ';
    }

    $pagination .= '<b>'.$page.'</b>';

    if($page + 1 <= $total) {
        $pagination .= ' | <a href="/page-'. ($page + 1) . \App\Model\Session::getSorterInfoForPagination() .'">'. ($page + 1) .'</a> ';
    }

    if($page + 2 <= $total) {
        $pagination .= ' | <a href="/page-'. ($page + 2) . \App\Model\Session::getSorterInfoForPagination() .'">'. ($page + 2) .'</a> ';
    }

// Проверяем нужна ли стрелка на последнюю страницу
    if ($page != $total) {
        $pagination .= ' <a href= "/page-' .$total . \App\Model\Session::getSorterInfoForPagination() .'">>></a>';
    }


// Вывод ссылок пагинации
    echo $pagination;
}