<?php

$page = $data['pagination']['currentPage'];
$total = $data['pagination']['totalPage'];

$pagination = '';

// Проверяем нужны ли стрелки назад
if ($page != 1){
    $pagination .= '<a href="/"><<</a>';
    $pagination .= ' <a href="./page-'. ($page - 1) .'"><</a> ';
}

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 2 > 0) {

    $pagination .= ' <a href= "/page-'. ($page - 2) .'">'. ($page - 2) .'</a> | ';


}

if($page - 1 > 0) {
    $pagination .= '<a href="/page-'. ($page - 1) .'">'. ($page - 1) .'</a> | ';
}

$pagination .= '<b>'.$page.'</b>';

if($page + 1 <= $total) {
    $pagination .= ' | <a href="/page-'. ($page + 1) .'">'. ($page + 1) .'</a>';
}

if($page + 2 <= $total) {
    $pagination .= ' | <a href="/page-'. ($page + 2) .'">'. ($page + 2) .'</a>';
}

// Проверяем нужны ли стрелки вперед
if ($page != $total) {
    $pagination .= ' <a href= "/page-'. ($page + 1) .'">></a> ';
    $pagination .= '<a href= "/page-' .$total. '">>></a>';
}


// Вывод меню
echo $pagination;