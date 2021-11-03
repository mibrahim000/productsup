<?php

namespace App\Tests\Unit\FileType;

use App\Exceptions\SpreedSheetException;
use App\Sheets\GoogleSheet;
use Google\Service\Sheets\Resource\Spreadsheets;
use Google\Service\Sheets\Resource\SpreadsheetsValues;
use Google\Service\Sheets\UpdateValuesResponse;
use Google\Service\Sheets\ValueRange;
use Monolog\Test\TestCase;

class GoogleSheetTest extends TestCase
{
    /** @test *
     * @throws \App\Exceptions\SpreedSheetException
     */
    public function send_data_to_google_sheet()
    {
        $inputData = [
            ['name', 'email'],
            ['mohamed', 'test@email.com'],
            ['Ali', 'test2@email.com'],
        ];

        $client = $this->createClientMocks($inputData);

        $googleSheet = new GoogleSheet($inputData, $client);

        $response = $googleSheet->sendData();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('sheet_id', $response);
        $this->assertArrayHasKey('range', $response);
        $this->assertArrayHasKey('updated_cells', $response);
        $this->assertEquals('123', $response['sheet_id']);
        $this->assertEquals('Sheet1!A1:B4', $response['range']);
        $this->assertEquals('6', $response['updated_cells']);

        $data = $googleSheet->readFromSheet($response['range']);

        $this->assertEquals(json_encode($data), json_encode($inputData));
    }

    protected function createClientMocks($data): object
    {
        $client = $this->createMock(\Google_Service_Sheets::class);

        $client->spreadsheets = $this->createMock(Spreadsheets::class);
        $res = new \stdClass();
        $res->spreadsheetId = '123';

        $updateValueResponse = $this->createMock(UpdateValuesResponse::class);
        $updateValueResponse->method('getUpdatedCells')->willReturn('6');

        $valueRange = $this->createMock(ValueRange::class);
        $valueRange->method('getValues')->willReturn($data);

        $client->spreadsheets->method('create')->willReturn($res);
        $client->spreadsheets_values = $this->createMock(SpreadsheetsValues::class);
        $client->spreadsheets_values->method('update')->willReturn($updateValueResponse);
        $client->spreadsheets_values->method('get')->willReturn($valueRange);

        return $client;
    }

    /** @test * */
    public function receive_exception_when_write_data()
    {
        $this->expectException(SpreedSheetException::class);

        $client = $this->createMock(\Google_Service_Sheets::class);

        $client->spreadsheets = $this->createMock(Spreadsheets::class);

        $client->spreadsheets->method('create')->will($this->throwException(new \Exception('test exception')));;

        $googleSheet = new GoogleSheet([], $client);

        $googleSheet->sendData();
    }
}