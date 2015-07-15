<?php
/**
 * Created by PhpStorm.
 */
namespace TD\Engine;


use TD\Controllers\Controller;
use TD\Exceptions\ApplicationException;

class Application
{
    const CONTROLLER            = 'Controller';
    const CONTROLLER_NAME_SPACE = '\\TD\\Controllers\\';

    /**
     * @var string Контроллер по умолчанию
     */
    public $defaultController;

    /**
     * @var string Действие по умолчанию
     */
    public $defaultAction;

    function __construct($config)
    {
        // разор конфига в поля приложения
        $this->defaultController = isset($config['defaultController']) ? $config['defaultController'] : 'site';
        $this->defaultAction     = isset($config['defaultAction']) ? $config['defaultAction'] : 'index';
    }


    /**
     * Запускает приложение
     */
    public function run()
    {
        $uri = substr($_SERVER["REQUEST_URI"], 1);
        if (empty($uri)) {
            $controller = self::CONTROLLER_NAME_SPACE . ucfirst($this->defaultController) . self::CONTROLLER;
            $action     = $this->defaultAction;
        } else {
            $uriData    = explode('/', $uri);
            $controller = self::CONTROLLER_NAME_SPACE . ucfirst($uriData[0]) . self::CONTROLLER;
            $action     = $uriData[1];

        }
        /**
         * @var Controller $controllerInstance
         */
        if (class_exists($controller)) {
            $controllerInstance = new $controller($action);
            $controllerInstance->run();
        } else {
            throw new ApplicationException(404);
        }
    }
}