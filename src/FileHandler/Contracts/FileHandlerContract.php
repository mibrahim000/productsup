<?php

namespace App\FileHandler\Contracts;

interface FileHandlerContract
{
    public function getFileData(): array;

    public function getFileMimeType(): string;

    public function setPath(string $path): self;
}