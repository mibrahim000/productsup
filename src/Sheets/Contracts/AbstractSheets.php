<?php

namespace App\Sheets\Contracts;

abstract class AbstractSheets implements SheetsContract
{
    public function __construct(protected array $data)
    {
    }
}