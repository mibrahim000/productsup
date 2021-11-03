<?php

namespace App\Tests\Unit\FileHandler;

use App\Exceptions\TypeNotSupportedException;
use App\FileHandler\FileHandlerFactory;
use App\FileHandler\LocalFileHandler;
use App\FileHandler\RemoteFileHandler;
use PHPUnit\Framework\TestCase;

class FileHandlerFactoryTest extends TestCase
{
    /** @test *
     * @throws TypeNotSupportedException
     */
    public function create_handler_by_type()
    {
        $factory = new FileHandlerFactory();

        $this->assertInstanceOf(LocalFileHandler::class, $factory->get('local'));
        $this->assertInstanceOf(RemoteFileHandler::class, $factory->get('remote'));
    }

    /** @test *
     * @throws TypeNotSupportedException
     */
    public function throw_exception_when_type_not_valid()
    {
        $this->expectException(TypeNotSupportedException::class);

        (new FileHandlerFactory())->get('wrong_type');
    }
}