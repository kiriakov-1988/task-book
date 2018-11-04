<?php

namespace App\Controller;


use App\View\View;

/**
 * Class Router
 * Обрабатывает все запросы к данном приложению и
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
     * Формат: Namespace/ClassName/actionName/parameters
     */
    const ROUTES = [
        '^add-task$' => 'Model/Task/addTask',
        '^log-in$'  => 'View/View/logIn',
        '^log-out$' => 'Model/Admin/logOutAdmin',
        '^authorize$' => 'Model/Admin/authorizeAdmin',
        '^edit-([1-9][0-9]*)$' => 'View/View/editTask/$1',
        '^save-task$' => 'Model/Task/saveEditTask',
        '^$' => 'View/View/getIndexPage',
    ];

    public function run(): void
    {
        $uri = $this->getURI();

        foreach (self::ROUTES as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);

                unset($internalRoute);

                $classNameWithNamespace = '\App\\' . array_shift($segments) . '\\' . array_shift($segments);

                $actionName = array_shift($segments);

                $parameters = $segments;

                unset($segments);

                $classObject = new $classNameWithNamespace;

                $result = call_user_func_array(array($classObject, $actionName), $parameters);

                if ($result != null) {
                    return;
                }

            }
        }

        header("HTTP/1.1 404 Not Found");
        $view = new View();
        $view->showError();
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