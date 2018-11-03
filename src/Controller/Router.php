<?php

namespace App\Controller;


/**
 * Class Router
 * Обрабоатвыет все запросы к данном приложению и
 * перенаправляет на нужный обрабротчик (класс/метод)
 *
 * В данном MVC приложении выполняет роль ФронтКонтроллера
 *
 * @package App\Controller
 */
class Router
{
    /**
     * Список поддерживаемых маршрутов
     */
    const ROUTES = [
        '...' => '...',
    ];

    /**
     * Неймспейс основных классов.
     * В данном случае, при нахождении всех классов в одном месте,
     * его можно и не указывать
     */
    const NAMESPACE_FOR_API_CLASS = '\App\Controller\\';

    public function run(): void
    {
        $uri = $this->getURI();

        foreach (self::ROUTES as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);

                unset($internalRoute);

                $controllerName = self::NAMESPACE_FOR_API_CLASS . array_shift($segments);

                $actionName = array_shift($segments);

                $parameters = $segments;

                unset($segments);

                $controllerObject = new $controllerName;

                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                if ($result != null) {
                    return;
                }

            }
        }

        header("HTTP/1.1 404 Not Found");
        // TODO show 404 error page
        return;
    }

    /**
     * Обрабатывает введенный адрес и возвращает его
     * @return string
     */
    private function getURI():string
    {
        if (! empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        return '';
    }
}