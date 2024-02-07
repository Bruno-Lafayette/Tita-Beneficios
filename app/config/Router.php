<?php

namespace app\config;

use app\helpers\Request;
use app\helpers\Uri;
use Exception;

class Router{

    private const CONTROLLER_NAMESPACE = 'app\\Controller';

    public static function load(string $controller, string $method){
        try{

            $ControllerNamespace = self::CONTROLLER_NAMESPACE.'\\'.$controller;
            if(!class_exists($ControllerNamespace)){
                throw new Exception("O Controller {$controller} não existe");
            }

            $ControllerInstance = new $ControllerNamespace;
            if(!method_exists($ControllerInstance, $method)){
                throw new Exception("Não existe o método {$method} no Controller {$controller}");
            }
            $ControllerInstance -> $method();

        } catch(\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public static function routes():array
    {
        return 
        [
            'get' => 
            [
                // 'teste' => self::load('HomeController', 'index'),
                '/sobre' => self::load('AboutController', 'index'),
                '/contatos' => fn()=> self::load('ContactController', 'index')
            ],
            'post' => 
            [

            ]
        ];
    }

    public static function execute(){
        try{
            $routes = self::routes();
            $request = Request::get();
            $uri = Uri::get('path');
            if (array_key_exists($uri, $routes[$request])){
                throw new Exception("A rota não existe");
            }
            $router = $routes[$request][$uri];
            $router();
        } catch (\Throwable $th){
            echo $th->getMessage();
        }
    }
}
