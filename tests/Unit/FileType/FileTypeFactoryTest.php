<?php

namespace App\Tests\Unit\FileHandler;

use App\Exceptions\MimeTypeNotSupportedException;
use App\FileType\FileTypeFactory;
use App\FileType\XmlFileType;
use PHPUnit\Framework\TestCase;

class FileTypeFactoryTest extends TestCase
{
    /** @test *
     * @throws MimeTypeNotSupportedException
     */
    public function create_handler_by_type()
    {
        $factory = new FileTypeFactory();

        $rawData = file_get_contents(__DIR__ . '/../../Data/coffee_feed_trimmed.xml');

        $this->assertInstanceOf(XmlFileType::class, $factory->get('xml', $rawData));
    }

    /** @test *
     * @throws MimeTypeNotSupportedException
     */
    public function throw_exception_when_type_not_valid()
    {
        $this->expectException(MimeTypeNotSupportedException::class);

        (new FileTypeFactory())->get('wrong_type', '');
    }
}