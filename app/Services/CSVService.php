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
            $row = array_map('trim', $row);
            
            if (empty($headers)) {
                $headers = $row;
                continue;
            }

            if(count($row) !== count($headers)) {
                continue;
            }

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

