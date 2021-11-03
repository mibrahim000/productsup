<?php

namespace App\FileType;

use App\Exceptions\MimeTypeNotSupportedException;
use App\FileType\Contracts\FileTypeContract;

class FileTypeFactory
{
    public function get(string $type,string $data): FileTypeContract
    {
        switch ($type) {
            case 'xml':
                return new XmlFileType($data);
            default:
                throw new MimeTypeNotSupportedException($type);
        }
    }
}