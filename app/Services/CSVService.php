<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;

class CSVService {
    // parse in a csv file from the browser
    public function parseCSV(UploadedFile $inputFile) {
        $handle = fopen($inputFile->getRealPath(), 'r');
        $data = [];
        $headers = [];

        while ((($row = fgetcsv($handle)) !== false)) {
            // remove whitespace from the end and start of each value to prevent unexpected issues
            $row = array_map('trim', $row);

            // detect the first row of the CSV and assign it to headers
            if (empty($headers)) {
                $headers = $row;
                continue;
            }

            // if the row count mismatches header count ignore that entry
            if(count($row) !== count($headers)) {
                continue;
            }

            // instead of data[0] make it data[header] i.e data['email'] for ease of use
            $data[] = array_combine($headers, $row);
        }
        fclose($handle);
        return $data;
    }

    public function validateHeaders(array $data, array $requiredHeaders) {
        // if no data exist, return false otherwise $data[0] is false
        if (empty($data)) {
            return false;
        }
        return array_keys($data[0]) === $requiredHeaders;
    }
}

