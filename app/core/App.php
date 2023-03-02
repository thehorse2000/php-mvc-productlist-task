<?php
class App
{
    protected $controller = 'products';
    protected $method = 'index';
    protected $params = [];
    public function __construct()
    {

        // RUN controller
        $url = $this->parseUrl();
        if (isset($url)) {
            $this->handleRouting($url);
        } else {
            $this->runDefaultController();
        }

    }
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
    public function requireController()
    {
        require_once('./app/controllers/' . $this->controller . '.php');
    }
    public function handleRouting($url)
    {
        // Controller
        $methodName = str_replace('-', '', $url[0]);

        if (file_exists('./app/controllers/' . strtolower($methodName) . '.php')) {
            $this->controller = strtolower($methodName);
            unset($url[0]);
        } else {
            $this->controller = 'PageNotFound';
        }
        $this->requireController();
        $controller = new $this->controller;

        // Method
        if (isset($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        $this->runController();
    }
    public function runController()
    {
        call_user_func_array([new $this->controller, $this->method], $this->params);
    }
    public function runDefaultController()
    {
        $this->requireController();
        call_user_func_array([new $this->controller, $this->method], $this->params);
    }
}