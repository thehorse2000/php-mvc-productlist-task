<?php
class BaseController
{
    public function model($model)
    {
        return new $model;
    }
    public function view($view, $data = [])
    {
        require_once('./app/views/' . $view . '.php');
    }
}