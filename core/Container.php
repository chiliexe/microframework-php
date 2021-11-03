<?php

namespace Core;

class Container
{

    public static function newController(string $controller) : object
    {
        $instance = "App\\Controllers\\" . $controller;
        return new $instance;
    }
}