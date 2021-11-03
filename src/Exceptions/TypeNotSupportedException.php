<?php

namespace App\Exceptions;

use Exception;

class TypeNotSupportedException extends Exception
{
    public function __construct($type)
    {
        parent::__construct("The given type '{$type}' is not supported. ", 403, null);
    }
}