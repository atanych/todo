<?php
namespace TD\Controllers;

use TD\Engine\Logger;
use TD\Exceptions\ApplicationException;

/**
 * Created by PhpStorm
 */
abstract class Controller
{
    const ACTION = 'action';
    public $action;

    function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * Событие перед action, если возвращает false, то останавливает исполнение
     */
    public function beforeAction()
    {
        return true;
    }

    /**
     * Запускает контроллер
     */
    public function run()
    {
        if ($this->beforeAction()) {
            $action = self::ACTION . ucfirst($this->action);
            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                throw new ApplicationException(404);
            }
            Logger::getInstance()->info($this);
            Logger::getInstance()->info(get_called_class());
        }
    }
}