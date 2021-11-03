<?php

namespace App\Exceptions;

use Exception;

class SpreedSheetException extends Exception
{
    public function __construct($error)
    {
        parent::__construct($error, 403, null);
    }
}