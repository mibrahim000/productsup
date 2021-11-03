<?php

namespace App\Sheets;

use App\Exceptions\SheetNotSupportedException;
use App\Sheets\Contracts\SheetsContract;

class SheetsFactory
{
    public function __construct(protected GoogleSpreedSheetClient $client,
    )
    {
    }

    public function get(string $type, array $data): SheetsContract
    {
        switch ($type) {
            case 'google_sheet':
                return new GoogleSheet($data, $this->client->getService());
            default:
                throw new SheetNotSupportedException($type);
        }
    }
}