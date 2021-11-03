<?php

namespace App\FileType\Contracts;

interface FileTypeContract
{
    public function getConvertedData(): array;
}