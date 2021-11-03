<?php

namespace App\FileHandler;

use App\Exceptions\TypeNotSupportedException;
use App\FileHandler\Contracts\FileHandlerContract;

class FileHandlerFactory
{
    public function get(string $type): FileHandlerContract
    {
        switch ($type){
            case 'local':
                return new LocalFileHandler();
            case 'remote':
                return new RemoteFileHandler();
            default:
                throw new TypeNotSupportedException($type);
        }
    }
}