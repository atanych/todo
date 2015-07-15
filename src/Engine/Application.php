<?php
/**
 * Created by PhpStorm.
 */
namespace TD\Engine;


use TD\Controllers\Controller;
use TD\Exceptions\ApplicationException;

class Application
{
    /**
     * Постфикс контроллера
     */
    const CONTROLLER = 'Controller';
    /**
     * Namespace для всех контроллеров
     */
    const CONTROLLER_NAME_SPACE = '\\TD\\Controllers\\';
    /**
     * Название контроллера, если не задано в конфигурации
     */
    const DEFAULT_CONTROLLER = 'site';

    /**
     * Название действия, если не задано в конфигурации
     */
    const DEFAULT_ACTION = 'index';

    /**
     * @var DbManager Компомент БД
     */
    public static $db;

    /**
     *  Инициализирует приложение
     *
     * @param array $config Параметры конфигурации
     *
     * @throws ApplicationException
     */
    public static function run($config)
    {
        // инициализацию компонент
        self::$db = new DbManager($config['db']['name'], $config['db']['username'], $config['db']['password']);

        $uri      = substr($_SERVER["REQUEST_URI"], 1);
        if (empty($uri)) {
            $controller = self::CONTROLLER_NAME_SPACE . ucfirst(isset($config['defaultController']) ? $config['defaultController'] : self::DEFAULT_CONTROLLER) . self::CONTROLLER;
            $action     = isset($config['defaultAction']) ? $config['defaultAction'] : self::DEFAULT_ACTION;
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