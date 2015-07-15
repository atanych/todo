<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Engine;

/**
 * Авторизация
 *
 * Class Auth
 * @package TD\Engine
 */
class Auth
{

    const TEST_NAME     = 'admin';
    const TEST_PASSWORD = 'admin';

    public static function identify($name, $password)
    {
        if ($name == self::TEST_NAME && $password == self::TEST_PASSWORD) {
            return true;
        } else {
            return false;
        }
    }
}