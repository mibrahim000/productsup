<?php

namespace App\FileType\Contracts;

abstract class AbstractFileType implements FileTypeContract
{
    public function __construct(protected string $data)
    {
    }
}