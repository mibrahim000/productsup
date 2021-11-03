<?php

namespace App\Tests\Unit\FileHandler;

use App\Exceptions\SheetNotSupportedException;
use App\Sheets\GoogleSheet;
use App\Sheets\GoogleSpreedSheetClient;
use App\Sheets\SheetsFactory;
use PHPUnit\Framework\TestCase;

class SheetsFactoryTest extends TestCase
{
    /** @test *
     * @throws SheetNotSupportedException
     */
    public function create_sheet_by_type()
    {
        $googleSpreedSheetClient = $this->createMock(GoogleSpreedSheetClient::class);

        $factory = new SheetsFactory($googleSpreedSheetClient);

        $data = [
            ['name', 'email'],
            ['mohamed', 'test@email.com'],
            ['Ali', 'test2@email.com'],
        ];

        $this->assertInstanceOf(GoogleSheet::class, $factory->get('google_sheet', $data));
    }

    /** @test *
     * @throws SheetNotSupportedException
     */
    public function throw_exception_when_sheet_type_not_supported()
    {
        $this->expectException(SheetNotSupportedException::class);

        $googleSpreedSheetClient = $this->createMock(GoogleSpreedSheetClient::class);

        (new SheetsFactory($googleSpreedSheetClient))->get('wrong_sheet', []);
    }
}