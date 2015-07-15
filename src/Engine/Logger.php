<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Engine;

/**
 * Примитивный логер, можно было сделать все красиво используя паттерн Change of Responsibility
 *
 * Class Logger
 * @package TD\Engine
 */
class Logger
{
    private static $instance;
    const LEVEL_INFO        = 'INFO';
    const LEVEL_DEBUG       = 'DEBUG';
    const LEVEL_ERROR       = 'ERROR';
    const DEFAULT_FILE_PATH = '/../log/app.log';


    private $file;

    private function __construct()
    {
        $this->file = $_SERVER['DOCUMENT_ROOT'] . self::DEFAULT_FILE_PATH;
    }

    private function __clone()
    {
    }

    /**
     * Возвращает единственный экземпляр класса
     * @todo возможно не самый лучший вариант для Singleton
     *
     * @return Logger
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Логирует сообщения типа ERROR
     *
     * @param mixed $data
     */
    public function error($data)
    {
        $this->write(self::LEVEL_ERROR, $data);
    }


    /**
     * Логирует сообщения типа DEBUG
     *
     * @param mixed $data
     */
    public function debug($data)
    {
        $this->write(self::LEVEL_DEBUG, $data);
    }

    /**
     * Логирует сообщения типа INFO
     *
     * @param mixed $data
     */
    public function info($data)
    {
        $this->write(self::LEVEL_INFO, $data);
    }

    /**
     * Записывает информацию в файл
     *
     * @param string $level Категория логирования
     * @param mixed  $data  Информация для логирования
     */
    private function write($level, $data)
    {
        $result = '[' . $level . '] ' . date('d-m-Y h:i') . ' ' . print_r($data, true) . PHP_EOL;
        file_put_contents($this->file, $result, FILE_APPEND);
    }
}