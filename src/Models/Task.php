<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Models;

class Task extends Model
{

    /**
     * @var int ID
     */
    public $id;

    /**
     * @var string название задачи
     */
    public $name;

    /**
     * @var string описание задачи
     */
    public $description;

    /**
     * @var int дедлайн задачи в секундах
     */
    public $deadline;

    public function rules()
    {
        return [
            [
                'fields'    => 'name, description, deadline',
                'validator' => 'required'
            ],
            [
                'fields'    => 'deadline',
                'validator' => 'integer'
            ]
        ];
    }

    /**
     * Выполняет действия перед сохранением записи
     */
    public function beforeSave()
    {
        parent::beforeSave();
    }

    /**
     * Возвращает дедлайн в формате d-m-Y
     *
     * @return string
     */
    public function getDeadline()
    {
        return date('d-m-Y', $this->deadline);
    }

    /**
     * Преобразовывает строчку даты в секунды
     */
    public function parseDeadline()
    {
        if (!empty($this->deadline)) {
            $this->deadline = (new \DateTime($this->deadline))->getTimestamp() + 1000;
        }
    }
}