<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Data;
use App\Imports\DataImport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|max:5120'
        ]);

        $extensions = array("xls", "xlsx");

        $file_type = array($request->file('excel_file')->getClientOriginalExtension());

        if (in_array($file_type[0], $extensions)) {

            try {
                $file = $request->file('excel_file');

                // Store the uploaded file
                $filePath = $file->store('temp', 'local');

                // Import data from the uploaded file
                $import = new DataImport();
                Excel::import($import, $filePath);

                // Load the spreadsheet file
                $filePath = storage_path('app/' . $filePath);
                $spreadsheet = IOFactory::load($filePath);

                // Get the active sheet
                $sheet = $spreadsheet->getActiveSheet();

                // Create a new instance of the DataImport class
                $import = new DataImport();

                // Import data from the active sheet
                $import->import($sheet);

                // Retrieve the imported data
                $importedData = $import->getData();

                // Process the importated data
                $entries = [];
                $i = 0;
                foreach ($importedData as $data) {

                    if ($data['Name']) {
                        
                        $data['Priority'] = Str::title($data['Priority']);
                        $priority = $data['Priority'] == "Low" ? 0 : ($data['Priority'] == "High" ? 1 : ($data['Priority'] == "Urgent" ? 2 : 0));

                        $entries[$i] = [
                            'created_at' => Carbon::parse($data['Date Received']),
                            'name' => $data['Name'],
                            'address' => $data['Address'],
                            'state' => $data['State Incarcerated'],
                            'unique_id' => $data['Inmate ID'],
                            'device' => Str::title($data['Device']),
                            'unlock_status' => Str::title($data['Unlock Status']),
                            'priority' => $priority,
                            'requested_device' => Str::title($data['Request For']),
                            'current_status' => Str::title($data['Current Status']),
                            'music_count' => $data['Music Count'],
                            'received_via' => Str::title($data['Received Via']),
                            'traking_number' => $data['Tracking Number'] ? $data['Tracking Number'] : null,
                            'notes' => $data['Note'],
                        ];
                        $i++;
                    }
                }
                // Insert the imported data into the database
                Data::insert($entries);

                // Delete the temporary file
                Storage::delete($filePath);

                echo json_encode(array("error" => 0, "message" => "Entries Successfully Imported"));
            } catch (\Exception $e) {
                echo json_encode(array("error" => 1, "message" => 'An error occurred while uploading the file: ' . $e->getMessage()));
            }
        } else {
            echo json_encode(array("error" => 1, "message" => 'File type must be xlsx or xls.'));
        }
    }

    public function exportExcel()
    {
        // Fetch data from the database
        $data = Data::select([
            'id',
            'created_at',
            'name',
            'address',
            'state',
            'unique_id',
            'device',
            'unlock_status',
            'notes',
            'traking_number',
            'priority',
            'requested_device',
            'current_status',
            'music_count',
            'received_via',
            'attachment',
        ])->get();

        // Custom column names for the XLSX file
        $columnNames = [
            'SN. ',
            'Date Received',
            'Name',
            'Address',
            'State Incarcerated',
            'Inmate ID',
            'Device',
            'Unlock Status',
            'Note',
            'Tracking Number',
            'Priority',
            'Request For',
            'Current Status',
            'Music Count',
            'Received Via',
            'Attachment',
        ];

        // Modify data before exporting
        $modifiedData = $data->map(function ($item) {
            // Add a string with every attachment
            $item['attachment'] = asset('assets/img/entries') . "/" . $item['attachment'];

            $item['priority'] = $item['priority'] == 0 ? 'Low' : ($item['priority'] == 1 ? 'High' : ($item['priority'] == 2 ? 'Urgent' : ''));

            return $item;
        });

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set the worksheet title
        $spreadsheet->getActiveSheet()->setTitle('Data');

        // Set column names in the first row of the worksheet
        $spreadsheet->getActiveSheet()->fromArray($columnNames, null, 'A1');

        // Set the data starting from the second row
        $spreadsheet->getActiveSheet()->fromArray($modifiedData->toArray(), null, 'A2');

        // Create a new Xlsx writer object
        $writer = new Xlsx($spreadsheet);

        // Set headers for the file download
        $fileName = 'Entries.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Save the spreadsheet to the output stream
        $writer->save('php://output');
    }
    public function conditionalExportExcel(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $type = $request->type;



        // Fetch data from the database
        $data = Data::select([
            'id',
            'created_at',
            'name',
            'address',
            'state',
            'unique_id',
            'device',
            'unlock_status',
            'notes',
            'traking_number',
            'priority',
            'requested_device',
            'current_status',
            'music_count',
            'received_via',
            'attachment',
        ]);

        if ($type == 'date') {
            $data = $data->whereBetween('created_at', [$start_date, $end_date]);
        } elseif ($type == 'month') {
            $data = $data->whereMonth('created_at', $month);
        }

        $data = $data->get();

        // Custom column names for the XLSX file
        $columnNames = [
            'SN. ',
            'Date Received',
            'Name',
            'Address',
            'State Incarcerated',
            'Inmate ID',
            'Device',
            'Unlock Status',
            'Note',
            'Tracking Number',
            'Priority',
            'Request For',
            'Current Status',
            'Music Count',
            'Received Via',
            'Attachment',
        ];

        // Modify data before exporting
        $modifiedData = $data->map(function ($item) {
            // Add a string with every attachment
            $item['attachment'] = asset('assets/img/entries') . "/" . $item['attachment'];

            $item['priority'] = $item['priority'] == 0 ? 'Low' : ($item['priority'] == 1 ? 'High' : ($item['priority'] == 2 ? 'Urgent' : ''));

            return $item;
        });

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set the worksheet title
        $spreadsheet->getActiveSheet()->setTitle('Data');

        // Set column names in the first row of the worksheet
        $spreadsheet->getActiveSheet()->fromArray($columnNames, null, 'A1');

        // Set the data starting from the second row
        $spreadsheet->getActiveSheet()->fromArray($modifiedData->toArray(), null, 'A2');

        // Create a new Xlsx writer object
        $writer = new Xlsx($spreadsheet);

        // Set headers for the file download
        $fileName = 'Entries.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Save the spreadsheet to the output stream
        $writer->save('php://output');
    }
}
