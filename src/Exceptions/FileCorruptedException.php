<?php

namespace App\Exceptions;

use Exception;

class FileCorruptedException extends Exception
{
    public function __construct($errorMessage)
    {
        parent::__construct("File Corrupted {$errorMessage}", 403, null);
    }
}