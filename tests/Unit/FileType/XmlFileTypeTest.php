<?php

namespace App\Tests\Unit\FileType;

use App\Exceptions\FileCorruptedException;
use App\FileType\XmlFileType;
use Monolog\Test\TestCase;

class XmlFileTypeTest extends TestCase
{
    /** @test *
     * @throws FileCorruptedException
     */
    public function convert_raw_data_to_array()
    {
        $rawData = file_get_contents(__DIR__ . '/../../Data/coffee_feed_trimmed.xml');

        $data = (new XmlFileType($rawData))->getConvertedData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('entity_id', $data[0]);
        $this->assertCount(12, $data);
    }

    /** @test **/
    public function throw_exception_when_content_is_corrupted()
    {
        $this->expectException(FileCorruptedException::class);

        $rawData = file_get_contents(__DIR__ . '/../../Data/coffee_feed_bad.xml');

        (new XmlFileType($rawData))->getConvertedData();
    }
}