<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Models;

use TD\Engine\Application;
use TD\Exceptions\ApplicationException;

/**
 * Class Model
 * @package TD\Models
 *
 * @method array rules()
 */
abstract class Model
{
    const VALIDATOR_REQ = 'required';
    const VALIDATOR_INT = 'integer';

    /**
     * @var array массив ошибок
     */
    protected $errors = [];

    /**
     * Присваивает аттрибутам значение согласно входному массиву данных
     *
     * @param array $rawAttributes вырой массив данных
     */
    public function setAttributes($rawAttributes)
    {
        foreach ($rawAttributes as $attrName => $attrValue) {
            if (property_exists($this, $attrName)) {
                $this->$attrName = $attrValue;
            }
        }
    }

    /**
     * Валидатор модели
     *
     * @return bool
     * @throws ApplicationException
     */
    public function validate()
    {
        for ($i = 0, $len = sizeof($this->rules()); $i < $len; $i++) {
            $row    = $this->rules()[$i];
            $fields = explode(',', $row['fields']);
            switch ($row['validator']) {
                case self::VALIDATOR_REQ:
                    for ($j = 0, $lenn = sizeof($fields); $j < $lenn; $j++) {
                        $fields[$j] = trim($fields[$j]);
                        if (empty($this->$fields[$j])) {
                            $this->errors[] = 'Поле ' . $fields[$j] . ' должно быть заполнено';
                        }
                    }
                    break;
                case self::VALIDATOR_INT:
                    for ($j = 0, $lenn = sizeof($fields); $j < $lenn; $j++) {
                        $fields[$j] = trim($fields[$j]);
                        if (!ctype_digit($this->$fields[$j])) {
                            $this->errors[] = 'Поле ' . $fields[$j] . ' должно быть числом';
                        }
                    }
                    break;
                default:
                    throw new ApplicationException(500, 'Валидатор ' . $row['validator'] . ' не известен');
            }
        }
        if (empty($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Сохраняет модель
     *
     * @return $this
     */
    public function save()
    {
        $this->beforeSave();

        $reflectionClass = new \ReflectionClass($this);
        $this->id        = Application::$db->insert(strtolower($reflectionClass->getShortName()), $this->asArray());
        return $this;
    }

    /**
     * Изменяет модель
     *
     * @return $this
     */
    public function update()
    {
        $reflectionClass = new \ReflectionClass($this);
        Application::$db->update(strtolower($reflectionClass->getShortName()), $this->asArray());
        return $this;
    }

    /**
     * Выполняет действия перед сохранением записи
     */
    protected function beforeSave()
    {

    }

    /**
     * Преобразовывает обьект в массив
     *
     * @return array
     */
    public function asArray()
    {

        $reflectionClass = new \ReflectionClass($this);
        $result          = [];
        foreach ($reflectionClass->getProperties(
            \ReflectionProperty::IS_PUBLIC
        ) as $property) {
            $property->setAccessible(true);
            if ($property->isStatic()) {
                continue;
            }
            $rawValue = $this->{$property->getName()};
            if (isset($rawValue)) {
                // если это примитивный тип, то присваиваем его результирующему массиву
                $value = $rawValue;
                // если это объект, вызываем рекурсивно себя же
                if (is_object($rawValue)) {
                    $value = $this->asArray($rawValue);
                    // если это массив, то пробегаем по нему и присваиваем его к результируюущему массиву
                } else if (is_array($rawValue)) {
                    $value = [];
                    for ($i = 0, $len = sizeof($rawValue); $i < $len; $i++) {
                        $value[] = $this->asArray($rawValue[$i]);
                    }
                }
                $result[$property->getName()] = $value;
            }
            $property->setAccessible(false);
        }
        return $result;
    }

    /**
     * Возвращает ошибки валидации
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}