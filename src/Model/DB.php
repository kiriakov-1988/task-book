<?php

namespace App\Model;


use App\View\View;

/**
 * Class DB
 * Данный класс предназначен для работы с базой данной.
 * Выполняет все запросы к ней.
 *
 * @package App\Model
 */
class DB
{
    /**
     * Настройки подключения к бае данных.
     * Подгружаются в константу класса, для наглядности при дальнейшем использовании.
     */
    const DB = CONFIG_DB;

    /**
     * Хранит ссылку на подключение к базе данных.
     * @var \PDO
     */
    private $connection;

    /**
     * DB constructor.
     * Выполняет подключение к базе данных.
     */
    public function __construct()
    {
        $this->connection = new \PDO(self::DB['driver'].':host='. self::DB['host'] .';dbname='. self::DB['name'] .';charset=utf8',
            self::DB['user'],
            self::DB['pass']);
    }

    /**
     * Возвращает массив с данными из базы даннных.
     * В случае ошибки или отсутсвия данных возвращается пустой масив.
     * Реализована возможность пагинации.
     * В случае обращения к несуществующей странице (при пагинации)
     * или неверным параметрам сортировки - возвращается ошибка 404
     *
     * @param int    $page       номер текущей страницы для пагинации
     * @param string $colName    имя столбика, по которому выполняется сортировка
     * @param string $direction  направление сортировки
     * @return array
     */
    public function getTasks(int $page, string $colName, string $direction): array
    {
        $page = intval($page);

        if ($page < 1) {
            // данная ситуация маловероятна так РОУТЕР должен не пропустить такое
            $page = 1;
        }

        $totalPage = $this->getTotalPage();

        if ($page > $totalPage) {
            return [];
        }

        $start = $page * View::MAX_PER_PAGE - View::MAX_PER_PAGE;

        $sqlQuery = 'SELECT * FROM `tasks` ORDER BY :col_num '. $direction . ' 
                       LIMIT :start, :offset';

        $stmt = $this->connection->prepare($sqlQuery);

        $offset = View::MAX_PER_PAGE;

        $colNum = $this->getColNum($colName);

        $stmt->bindParam(':col_num',$colNum, \PDO::PARAM_INT);
        $stmt->bindParam(':start',$start, \PDO::PARAM_INT);
        $stmt->bindParam(':offset',$offset, \PDO::PARAM_INT);

        if ($success = $stmt->execute()) {
            return [
                'success' => true,
                'listOfTasks' => $stmt->fetchAll(\PDO::FETCH_ASSOC),
                'pagination'  => [
                    'currentPage'  => $page,
                    'totalPage' => $totalPage
                ]
            ];
        }

        return [
            'success' => false
        ];
    }

    /**
     * Добавляет новую задачу в БД
     *
     * @param array $taskData
     * @return bool
     */
    public function addTask(array $taskData)
    {
        $sqlQuery = 'INSERT INTO `tasks` (`user_name`, `email`, `task_text`, `img`)
                                  VALUES (:user_name, :email, :task_text, :img)';

        $stmt = $this->connection->prepare($sqlQuery);

        $stmt->bindParam(':user_name',$taskData['user_name']);
        $stmt->bindParam(':email',$taskData['email']);
        $stmt->bindParam(':task_text',$taskData['task_text']);
        $stmt->bindParam(':img',$taskData['img']);

        if ($stmt->execute()) {

            return true;

        } else {

            return false;

        }
    }

    /**
     * Возвращет данные о задаче из БД для их редактировани
     *
     * @param int $id
     * @return array
     */
    public function getTask(int $id):array
    {
        $sqlQuery = 'SELECT * FROM `tasks` WHERE `id` = ' . $id;

        $stmt = $this->connection->prepare($sqlQuery);

        if ($success = $stmt->execute()) {

            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $data;
            }
        }

        return [];
    }

    /**
     * Вносит обновленные данные о задаче.
     * Редактировать можно текст самой задачи и выставлять отметку о выполнении
     *
     * @param array $taskData
     * @return bool
     */
    public function editTask(array $taskData)
    {
        if ($taskData['status']) {
            $sqlQuery = 'UPDATE `tasks` 
                            SET `task_text` = :task_text, `status` = "completed" 
                            WHERE id = :id';
        } else {
            $sqlQuery = 'UPDATE `tasks` 
                            SET `task_text` = :task_text
                            WHERE id = :id';
        }

        $stmt = $this->connection->prepare($sqlQuery);

        $stmt->bindParam(':id',$taskData['id']);
        $stmt->bindParam(':task_text',$taskData['task_text']);

        if ($stmt->execute()) {

            return true;

        } else {

            return false;

        }
    }

    /**
     * Производит подсчет общего количества страниц для пагинации
     * В случае пустой БД (и ошибки запроса) возвращается 1,
     * так как при пагинации всегда текущая страница как минимум первая.
     * Эти два значения соответствующе сравниваются в getTasks()
     *
     * @return int
     */
    private function getTotalPage():int
    {
        $sqlQuery = 'SELECT COUNT(`id`) as `total` FROM `tasks`';

        $stmt = $this->connection->query($sqlQuery);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result['total']) {

            return intval(($result['total'] - 1) / View::MAX_PER_PAGE) + 1;

        } else {
            return 1;
        }
    }

    /**
     * Возвращает номер колонки в таблице БД
     * В числа преобразуются только колонки доступные для сортировки.
     * При изменении структуры таблицы в БД здесь необходимы обновления.
     *
     * @param string $colName
     * @return int
     */
    private function getColNum(string $colName): int
    {
        switch ($colName) {
            case 'user':
                return 2;
            case 'email':
                return 3;
            case 'status':
                return 6;
//          case 'id':
//              return 1;
            // можно просто проверять на 'id' или по дефолту возвращать сразу 1
            default:
                return 1;
        }
    }

}