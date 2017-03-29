<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.03.17
 * Time: 11:42
 */
class Router
{

    private $routes;

    public function __construct()
    {
        $routersPath = ROOT.'/config/routes.php';
        $this->routes = include($routersPath);
    }

    /**
     * Returns request string
     * $return string
     */
    private function getURI() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return $uri = trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    public function run()
    {
        $uri = $this->getURI();


        foreach ($this->routes as $uriPattern => $path) {
            if  (preg_match("~$uriPattern~", $uri)) {

                $segments = explode('/', $path);

                $controllerName = array_shift($segments).'Controller';
                $controllerName = ucfirst( $controllerName );

                $actionName = 'action'. ucfirst(array_shift($segments));

                $controllerFile = ROOT.'/controllers/'.
                    $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once ($controllerFile);
                }

                //создать объект запустить action
                $controllerObject = new $controllerName;
                $result = $controllerObject->$actionName();
                if ($result != null){
                    break;
                }
            } else {
                $controllerName = '404Controller';

                $actionName = 'actionNotFound';
            }
        }
        echo ("$controllerName/$actionName");

    }
}