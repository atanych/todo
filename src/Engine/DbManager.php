<?php
/**
 * Created by PhpStorm.
 */
namespace TD\Engine;

use PDO;

class DbManager
{
    const MODEL_NAME_SPACE = '\\TD\\Models\\';
    private $connection;

    public function __construct($dbName, $username, $password)
    {
        $this->connection = new PDO(
            'mysql:host=localhost;dbname=' . $dbName,
            $username,
            $password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    }

    /**
     * Находит все элементы в таблице по фильтрам
     *
     * @param   string $tableName Название таблицы
     * @param array    $filters   Фильтры
     *
     * @return array
     */
    public function findAll($tableName, $filters = null)
    {
        //@todo забил жестко сортировку по ID :(
        $sql = 'SELECT * FROM ' . $tableName . ' ORDER BY id DESC';
        $sth = $this->connection->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_CLASS, $this->getClassName(ucfirst($tableName)));
    }

    /**
     * Находит запись по ID
     *
     * @param string $tableName Название таблицы
     * @param int    $id        ID записи
     *
     * @return mixed
     */
    public function findByPk($tableName, $id)
    {
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = ?';
        $sth = $this->connection->prepare($sql);
        $sth->execute([$id]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Создает новую запись
     *
     * @param string $tableName Название таблицы
     * @param array  $data      Ассоициативный массив для записи
     */
    public function insert($tableName, $data)
    {
        $fields    = array_keys($data);
        $fieldlist = implode(',', $fields);
        $values    = array_values($data);
        $qs        = str_repeat("?,", count($fields) - 1);
        $sql       = 'INSERT INTO ' . $tableName . ' (' . $fieldlist . ') VALUES(' . $qs . '?)';
        $sth       = $this->connection->prepare($sql);
        Logger::getInstance()->info($sql);
        Logger::getInstance()->info($values);
        Logger::getInstance()->info($fieldlist);
        Logger::getInstance()->info($qs);
        $sth->execute($values);
    }

    /**
     *  Возвращает название класса с неймспейсом
     *
     * @param $name
     *
     * @return string
     */
    private function getClassName($name)
    {
        return self::MODEL_NAME_SPACE . $name;
    }

}