<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// class DataImport implements ToArray, WithStartRow
// {
//     protected $data = [];

//     public function array(array $array)
//     {
        
//         for($i = 0; $i < count($array); $i++){
//             $this->data[] = $array[$i];
//         }
//     }

class DataImport
{
    protected $data = [];
    protected $columnNames = [];

    public function import(Worksheet $sheet)
    {
        $rows = $sheet->toArray();
        // Get the column names from the first row
        $this->columnNames = array_shift($rows);

        foreach ($rows as $row) {
            $rowData = [];

            // Map the values to column names
            foreach ($row as $index => $value) {
                $columnName = $this->columnNames[$index] ?? '';

                // Add the value to the corresponding column name in the row data
                $rowData[$columnName] = $value;
            }

            $this->data[] = $rowData;
        }
    }
    public function getData()
    {
        return $this->data;
    }

    public function startRow(): int
    {
        return 2; // Skip the header row
    }
}
