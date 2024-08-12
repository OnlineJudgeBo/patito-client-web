<?php

namespace PatitoOnlineJudge\Core\Application\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    public function __construct($message = "Ocurrió un error.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
