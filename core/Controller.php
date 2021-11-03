<?php

namespace Core;

use stdClass;

abstract class Controller
{
    private string $viewPath;
    protected string $layoutPath = "";
    protected stdClass $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    protected function setLayoutPath($layoutpath){
        $this->layoutPath = __DIR__ . "/../app/Views/" . $layoutpath . ".phtml";
    }
    protected function render(string $filename)
    {
        $this->viewPath = __DIR__ . "/../app/Views/" . $filename . ".phtml";
        if(!empty($this->layoutPath)){
            return $this->renderLayout();
        }else {
            return $this->renderBody();
        }
    }

    protected function renderBody()
    {
        if(!file_exists($this->viewPath))
            die("template does not exists '' ");

        return require_once $this->viewPath;
    }
    private function renderLayout()
    {
        if(!file_exists($this->layoutPath))
            die("template layout does not exists '' ");

        return require_once $this->layoutPath;
    }
    protected function redirect(string $path)
    {
        header("Location:$path");
    }
}