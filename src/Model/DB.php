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

    public function getTasks(): array
    {
        $sqlQuery = 'SELECT * FROM `tasks` ORDER BY `id`';

        $stmt = $this->connection->prepare($sqlQuery);

        if ($success = $stmt->execute()) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return [];
    }
}