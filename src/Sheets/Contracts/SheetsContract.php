<?php

namespace App\Sheets\Contracts;

interface SheetsContract
{
    public function sendData(): array;

    public function readFromSheet(string $range): array;
}