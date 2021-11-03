<?php


namespace App\FileType;

use App\Exceptions\FileCorruptedException;
use App\FileType\Contracts\AbstractFileType;
use SimpleXMLElement;

class XmlFileType extends AbstractFileType
{
    public function getConvertedData(): array
    {
        try {
            $data = simplexml_load_string($this->data, SimpleXMLElement::class, LIBXML_NOCDATA);

            $response = [];

            foreach ($data as $row) {
                $response[] = array_map(fn($item) => (string)$item, (array)$row);
            }

            return $response;
        } catch (\Exception $e) {
            throw new FileCorruptedException($e->getMessage());
        }
    }

}