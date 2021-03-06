<?php

namespace App\View;


use App\Model\DB;
use App\Model\Session;
use App\Model\Sorter;

/**
 * Class View
 * Данный класс отвечает за генерацию различных страниц.
 * Осуществляет связь между ФронтКонтроллером и шаблонами представления контента на страницах сайта.
 * При необходимости с подгрузкой соответствующих данных из Модели (класс DB)
 *
 * @package App\View
 */
class View
{
    /**
     * Максимальное количество задач на странице .
     */
    const MAX_PER_PAGE = 3;

    /**
     * Отображает главную страницу сайта
     * Дополнительно можно так же передать вторым параметром определенные данные.
     * В случае указания несуществующей странички (при пагинации) - генерируется ошибка 404
     * К главной странице можно обращаться и без указания страницы №1, но если заданы параметры сортирровки,
     * то префикс "page-1" всегда присутствует в ссылке (так построена маршрутизация)
     *
     * @param string $page       номер страницы в пагинации
     * @param string $colName    колонка, которая сортируется
     * @param string $direction  по умолчанию, сортировка по возростанию
     * @return bool|null
     */
    public function getIndexPage(string $page, string $colName = 'sort-id', string $direction = 'ASC'): ?bool
    {
        $sorter = Sorter::setOrder($page, $colName, $direction);

        if (!count($sorter)) {
            // в случае ошибки, например неверный параметр при сортировке
            return null;
        }

        try {
            $db = new DB();
            $result = $db->getTasks($sorter['page'], $sorter['colName'], $sorter['direction']);

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

    /**
     * Отвечает за редактирование определенной записи.
     * Доступно только для админа.
     * В случае попытки редактирования несуществующей задачи - будет выдана 404 ошибка.
     *
     * @param int $id
     * @return bool|null
     */
    public function editTask(int $id): ?bool
    {
        // Для возврата на ту же страничку после редактирования задачи.
        // Здесь не использует класс SESSION, так как используется только раз
        // и из-за промежуточной страницы с формой редактирования
        $_SESSION['refererLink'] = $_SERVER['HTTP_REFERER'];

        if (isset($_SESSION['adminMarker'])) {
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
        } else {
            header('Location: /');
            return true;
        }
    }

    /**
     * Служит для отобажения формы авторизации
     *
     * @return bool
     */
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

    /**
     * Генерирует различные страницы, которые отдаются пользователю.
     * Переданные параметры используются далее в соответствующих шаблонах
     *
     * @param string $content_view  шаблон, который будет отображаться
     * @param array|null $data      информация, отображаемая в шаблоне
     */
    private function generate(string $content_view, array $data = null): void
    {
        include '../templates/template_view.php';
    }
}