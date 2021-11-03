<?php

namespace App\Tests\Unit\FileHandler;

use App\Exceptions\MimeTypeNotSupportedException;
use App\Exceptions\NotFoundException;
use App\FileHandler\LocalFileHandler;
use PHPUnit\Framework\TestCase;

class LocalFileHandlerTest extends TestCase
{
    /** @test */
    public function return_not_found_if_file_not_exist()
    {
        $this->expectException(NotFoundException::class);

        (new LocalFileHandler())->setPath('file_not_found.xml');
    }

    /** @test *
     * @throws NotFoundException
     */
    public function get_data_as_an_array()
    {
        $file = __DIR__ . '/../../Data/coffee_feed_trimmed.xml';

        $data = (new LocalFileHandler())->setPath($file)->getFileData();

        $this->assertIsArray($data);
        $this->assertCount(12, $data);
        $this->assertArrayHasKey('entity_id', $data[0]);
    }

    /** @test * */
    public function validate_file_type_is_supported()
    {
        $file = __DIR__ . '/../../Data/coffee_feed_trimmed.xml';

        $mimeType = (new LocalFileHandler())->setPath($file)->getFileMimeType();

        $this->assertEquals('xml', $mimeType);
    }

    /** @test * */
    public function receive_exception_when_file_mime_type_not_supported()
    {
        $this->expectException(MimeTypeNotSupportedException::class);

        $file = __DIR__ . '/../../Data/coffee_feed_bad.xml';

        (new LocalFileHandler())->setPath($file)->getFileData();
    }
}