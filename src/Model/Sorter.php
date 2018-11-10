<?php

namespace App\Model;


/**
 * Class Sorter
 * Выполняет базовую предобработку параметров сортировки
 * Данный функционал вынесен из View::getIndexPage() с целью
 * соответствия принципам MVC (отсутсвие обработки данных в ВИДЕ).
 *
 * @package App\Model
 */
class Sorter
{
    /**
     * Задает параметры сортировки с предварительной их предобработкой
     * При клике по первому столбику в таблице происходит сбрасывание параметров сортировки
     *
     * @param string $page        текущая страница в пагинации
     * @param string $colName     сортируемый столбик
     * @param string $direction   направление сортировки
     * @return array
     */
    public static function setOrder(string $page, string $colName, string $direction): array
    {
        $page = (int)substr($page, 5);
        if (!$page) {
            // на случай пустой строки "" (главная страница, в первый параметр попадает именно пустая строка),
            // указание параметра по умолчанию "page-1" не обрабатывается должным образом
            $page = 1;
        }

        $colName = substr($colName, 5);
        if (!in_array($colName, ['id', 'user', 'email', 'status'])) {
            // Запрос на несуществующую страницу (в сортировке)
            return [];
        }

        if ($direction == 'desc') {
            // в принципе SQL не чувствителен к регистру для таких ключевых слов в запросе
            $direction = 'DESC';
        }

        if ($colName == 'id') {
            Session::delSorterInfo();
        } else {
            Session::addSorterInfo($colName, $direction);
        }

        return [
            'page'      => $page,
            'colName'   => $colName,
            'direction' => $direction
        ];
    }
}