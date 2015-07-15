<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Engine;

class APIHelper
{

    /**
     * Возращает успешный ответ от сервера
     *
     * @param mixed $data Тело ответа
     */
    public static function success($data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Возращает ошибку от сервера
     *
     * @param int    $status Статус ошибки
     * @param  mixed $data   Тело ответа
     */
    public static function error($status, $data)
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}