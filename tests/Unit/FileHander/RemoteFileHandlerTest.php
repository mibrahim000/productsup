<?php
namespace App\Tests\Unit\FileHandler;

use App\Exceptions\MimeTypeNotSupportedException;
use App\Exceptions\NotFoundException;
use App\FileHandler\LocalFileHandler;
use App\FileHandler\RemoteFileHandler;
use PHPUnit\Framework\TestCase;

class RemoteFileHandlerTest extends TestCase
{
    /** @test */
    public function return_not_found_if_file_not_exist()
    {
        $this->expectException(NotFoundException::class);

        (new RemoteFileHandler())->setPath('ftp://pupDev:pupDev2018@transport.productsup.io/not_found.xml');
    }

    /** @test *
     * @throws NotFoundException
     */
    public function get_data_as_an_array()
    {
        $url = 'ftp://pupDev:pupDev2018@transport.productsup.io/coffee_feed_trimmed.xml';

        $remoteFile = (new RemoteFileHandler())->setPath($url);

        $data = $remoteFile->getFileData();

        $this->assertIsArray($data);

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

        (new RemoteFileHandler())->setPath($file)->getFileData();
    }
}