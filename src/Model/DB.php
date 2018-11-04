<?php

namespace App\Model;


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

    // TODO сортировка и пагинация
    public function getTasks(): array
    {
        $sqlQuery = 'SELECT * FROM `tasks` ORDER BY `id`';

        $stmt = $this->connection->prepare($sqlQuery);

        if ($success = $stmt->execute()) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return [];
    }

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
}