<?php
namespace TD\Controllers;

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
        }
    }

    /**
     * Рендерит шаблон
     *
     * @param string $name Имя шаблона
     *
     * @throws ApplicationException
     */
    public function render($name, $data = null)
    {
        $view = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $name . '.php';
        if (file_exists($view)) {
            if (is_array($data)) {
                extract($data, EXTR_PREFIX_SAME, 'data');
            }
            require($view);
        } else {
            throw new ApplicationException(500, 'Представление ' . $name . ' для контроллера ' . get_called_class() . ' не найдено');
        }
    }
}