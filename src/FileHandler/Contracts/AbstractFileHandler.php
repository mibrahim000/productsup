<?php

namespace App\FileHandler\Contracts;

use App\Exceptions\NotFoundException;
use App\FileType\FileTypeFactory;

abstract class AbstractFileHandler implements FileHandlerContract
{
    protected array $data;

    protected string $rawData;

    protected string $path;

    protected string $mimeType = '';

    public function getFileMimeType(): string
    {
        return $this->mimeType;
    }

    public function setPath(string $path): self
    {
        if (!file_exists($path)) {
            throw new NotFoundException($path);
        }

        $this->path = $path;

        $this->setMimeType($this->path);

        return $this;
    }

    public function getFileData(): array
    {
        $this->data = (new FileTypeFactory())->get($this->mimeType, $this->getFileContent())->getConvertedData();

        return $this->data;
    }

    protected function setMimeType(): void
    {
        $fh = fopen('php://memory', 'w+b');
        fwrite($fh, $this->getFileContent());

        $contentType = mime_content_type($fh);

        fclose($fh);

        $res = explode('/', $contentType);

        $this->mimeType = end($res);
    }

    protected function getFileContent(): string
    {
        if (empty($this->rawData))
            $this->rawData = file_get_contents($this->path);

        return $this->rawData;
    }
}