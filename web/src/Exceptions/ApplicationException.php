<?php
/**
 * Created by PhpStorm.
 */

namespace TD\Exceptions;


class ApplicationException extends \Exception
{

    // дописать коды
    public $messages = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
    ];

    function __construct($code, $message = null)
    {
        if ($message === null) {
            $message = $this->messages[$code];
        }
        parent::__construct($message, $code);
    }

}