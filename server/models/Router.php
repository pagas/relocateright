<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 04/11/2017
 * Time: 11:50
 */

class Router
{
    private $controller;
    private $action;
    private $queryParams = array();
    private $actionParams = array();
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function process()
    {
        $this->assignActionParams();
        $this->assignQueryParams();

        $this->runController();
        $this->runAction();
        $this->renderMainView();
    }

    private function renderMainView() {
        $layout = empty($this->controller->getLayout()) ? 'layout' : $this->controller->getLayout();

        if (!self::isAjax()) {
            //extract($this->queryParams);
            //extract($this->actionParams);
            extract($this->controller->getParams());
            require(Config::SERVER_PATH . "views/" . $layout . ".phtml");
        }
    }

    private function runController() {
        $parsedUrl = $this->parseUrl($this->url);
        $controllerName = Config::DEFAULT_CONTROLLER;

        // The controller is the 1st URL parameter
        if (!empty($parsedUrl[0])) {
            $controllerName = $parsedUrl[0];
        }

        $controllerClass = $this->dashesToCamel($controllerName) . 'Controller';

        if (file_exists(Config::SERVER_PATH . 'controllers/' . $controllerClass . '.php')) {
            $this->controller = new $controllerClass;
        } else {
            $this->redirect('error');
        }
    }

    private function assignActionParams() {
        $parsedUrl = $this->parseUrl($this->url);
        if (!empty($parsedUrl[1])) {
            $this->actionParams = array_slice($parsedUrl, 2);
        }
    }

    private function assignQueryParams() {
        $parsedUrl = parse_url($this->url);
        if(!empty($parsedUrl['query'])) {
            $params = explode('&', $parsedUrl['query']);
            foreach ($params as $param) {
                $field = explode('=', $param);
                if (count($field) == 2) {
                    $this->queryParams[$field[0]] = $field[1];
                }
            }
        }
    }

    private function runAction() {
        $action = 'index';
        $parsedUrl = $this->parseUrl($this->url);
        // The action is the 2st URL parameter
        if (!empty($parsedUrl[1])) {
            $action = $parsedUrl[1];
        }
        if (method_exists($this->controller, $action) ) {
            $this->action = $action;
            $this->controller->{$action}(array(
                'queryParams' => $this->queryParams,
                'actionParams' => $this->actionParams
            ));
        } else {
            $this->redirect('error');
        }
    }

    public function parseUrl()
    {
        $parsedUrl = parse_url($this->url);
        $parsedUrl["path"] = ltrim($parsedUrl["path"], "/");
        $parsedUrl["path"] = trim($parsedUrl["path"]);
        $explodedUrl = explode("/", $parsedUrl["path"]);
        return $explodedUrl;
    }

    private function dashesToCamel($text)
    {
        $text = str_replace('-', ' ', $text);
        $text = ucwords($text);
        $text = str_replace(' ', '', $text);
        return $text;
    }


    static public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }
}