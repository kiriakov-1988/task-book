<?php

namespace App\Model;


/**
 * Class Session
 * Реализует некоторые удобства на сайте
 *
 * @package App\Model
 */
class Session
{
    /**
     * Добавляет в Сессию сообщение об успехе или ошибке.
     * Данной сообщение в последствии затирается из страницы при помощи JS
     *
     * @param string $message
     * @param bool $success
     */
    public static function addSessionStatus(string $message, bool $success = false): void
    {
        $_SESSION['status'] = [
            'success' => $success,
            'message' => $message
        ];
    }

    /**
     * Добавляет метку об авторизации пользователя (админа)
     */
    public static function addAdminMarker():void
    {
        $_SESSION['adminMarker'] = true;
    }

    /**
     * Удаляет метку авторизации.
     * Пользователь считается не авторизованным
     */
    public static function delAdminMarker():void
    {
        if (isset($_SESSION['adminMarker'])) {
            unset($_SESSION['adminMarker']);
        }
    }

    /**
     * Добавляет информацию о текущаих параметрах в сессию.
     * Данные используются для организации последующих ссылок в пагинации и так далее
     *
     * @param string $colName    сортируемый столбец
     * @param string $direction  направление сортировки
     */
    public static function addSorterInfo(string $colName, string $direction = 'ASC'): void
    {
        $_SESSION['sorter'] = [
            'colName'   => $colName,
            'direction' => $direction
        ];
    }

    /**
     * Удаляет данные о сортировке.
     * Настройки сортировки сбрасываются к дефолтным - при клике на первый столбик.
     */
    public static function delSorterInfo(): void
    {
        if (isset($_SESSION['sorter'])) {
            unset($_SESSION['sorter']);
        }
    }

    /**
     * Возвращает строку, которая добавляется к ссылкам в пагинации.
     * Содержит название сортируемого столба и, в случае сортировки по убыванию, направление сортировки
     *
     * @return string
     */
    public static function getSorterInfoForPagination(): string
    {
        if (isset($_SESSION['sorter'])) {

            $url  = '/sort-' . $_SESSION['sorter']['colName'];

            if ($_SESSION['sorter']['direction'] == 'DESC') {
                $url  .= '/desc';
            }

            return $url;
        }

        return '';
    }

    /**
     * Реализует разнонаправленную сортировку.
     * Возвращает строку, которая добавляется к ссылке в заголовке отсортировано столбца.
     * При повторном клике по заголовку происходит переключение направления сортировки.
     * Изначально столбец сперва сортируется по возрастанию.
     *
     * @return string
     */
    public static function getDirectionForThLink(): string
    {
        if (isset($_SESSION['sorter'])) {

            if ($_SESSION['sorter']['direction'] == 'DESC') {
                $url  = '';
            } else {
                $url  = '/desc';
                $_SESSION['sorter']['direction'] = 'ASC';
            }

            return $url;
        }

        return '';
    }
}