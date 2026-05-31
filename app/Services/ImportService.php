<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;

class ImportService
{
    /**
     * Membaca file CSV atau Excel (.xlsx/.xls) dan mengembalikan
     * array of rows. Baris pertama dianggap header dan dilewati.
     * Setiap row adalah array dengan index numerik (0-based).
     *
     * @param  UploadedFile $file
     * @return array<int, array<int, string>>
     */
    public function readFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv' || $extension === 'txt') {
            return $this->readCsv($file->getRealPath());
        }

        return $this->readExcel($file->getRealPath());
    }

    private function readCsv(string $path): array
    {
        $rows   = [];
        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);

        if ($header && str_starts_with($header[0], "\xEF\xBB\xBF")) {
            $header[0] = substr($header[0], 3);
        }

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = array_map('strval', $row);
        }

        fclose($handle);

        return $rows;
    }

    private function readExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = [];
        $firstRow    = true;

        foreach ($sheet->getRowIterator() as $row) {
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = (string) ($cell->getValue() ?? '');
            }

            if (empty(array_filter($rowData, fn($v) => $v !== ''))) {
                continue;
            }

            $rows[] = $rowData;
        }

        return $rows;
    }
}