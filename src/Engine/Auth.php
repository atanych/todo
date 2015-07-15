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

    public static function check($name, $password)
    {
        if ($name == self::TEST_NAME && $password == self::TEST_PASSWORD) {
            return true;
        } else {
            header('WWW-Authenticate: Basic realm="Bad auth"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You press cancel';
            exit;
        }
    }
}