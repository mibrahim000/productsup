<?php

namespace App\Exceptions;

use Exception;

class SheetNotSupportedException extends Exception
{
    public function __construct($type)
    {
        parent::__construct("The given sheet type '{$type}' is not supported. ", 403, null);
    }
}