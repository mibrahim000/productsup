<?php

namespace App\Exceptions;

use Exception;

class MimeTypeNotSupportedException extends Exception
{
    public function __construct($type)
    {
        parent::__construct("The mime type  of the given file is '{$type}' and is not yet supported. ", 403, null);
    }
}