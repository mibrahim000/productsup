<?php

namespace App\Sheets;

use App\Exceptions\SpreedSheetException;
use App\Sheets\Contracts\AbstractSheets;
use Google_Service_Sheets;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_ValueRange;

class GoogleSheet extends AbstractSheets
{
    protected string $spreedSheetId;

    public function __construct(
        array $data,
        protected Google_Service_Sheets $service,
    )
    {
        parent::__construct($data);
    }

    public function readFromSheet(string $range): array
    {
        try {
            $values = $this->service
                ->spreadsheets_values
                ->get($this->spreedSheetId, $range)
                ->getValues();

            return $values ?? [];

        } catch (\Exception $e) {
            throw new SpreedSheetException($e->getMessage());
        }
    }

    public function sendData(): array
    {
        try {
            $this->createSheet('Productsup-' . date('Y-m-d-h:ia'));
            return $this->writeToSheet($this->prepareData($this->data));
        } catch (\Exception $e) {
            throw new SpreedSheetException($e->getMessage());
        }
    }

    protected function createSheet($title): string
    {
        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $title
            ]
        ]);

        return $this->spreedSheetId = $this->service->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ])->spreadsheetId;
    }

    protected function writeToSheet($data, $sheetName = 'Sheet1'): array
    {
        try {
            $range = sprintf('%s!A1:%s%d',
                $sheetName,
                $this->columnToLetter(count($data[0])), count($data)
            );

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $data
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED',
            ];

            return [
                'sheet_id' => $this->spreedSheetId,
                'range' => $range,
                'updated_cells' => $this->service
                    ->spreadsheets_values
                    ->update($this->spreedSheetId, $range, $body, $params)
                    ->getUpdatedCells(),
            ];

        } catch (\Exception $e) {
            throw new SpreedSheetException($e->getMessage());
        }
    }

    protected function columnToLetter(string $column): string
    {
        $letter = '';

        while ($column > 0) {
            $temp = ($column - 1) % 26;
            $letter = chr($temp + 65) . $letter;
            $column = ($column - $temp - 1) / 26;
        }

        return $letter;
    }

    protected function prepareData(array $data): array
    {
        $response = [];

        foreach ($data as $row) {
            if (empty($response))
                $response[] = array_keys($row);

            $response[] = array_values($row);
        }

        return $response;
    }
}