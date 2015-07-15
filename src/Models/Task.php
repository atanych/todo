<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Models;

class Task extends Model
{
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
        $this->deadline = $this->deadline / 1000;
    }

    /**
     * Возвращает дедлайн в формате времени
     *
     * @return string
     */
    public function getDeadline()
    {
        return date('d-m-Y', $this->deadline);
    }
}