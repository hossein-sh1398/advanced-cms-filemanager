<?php

namespace App\Utility\Export;

use App\Utility\Excel\SimpleXLSXGen;

class Export
{
    public function excel($data, $columns)
    {
        $xlsx = SimpleXLSXGen::fromArray(array_merge($columns, $data));

        return $xlsx->downloadAs('excel.xlsx');
    }

    public function csv($data, $cols)
    {
        $fileName = 'csv.csv';

        $headers = array(
            'Content-Type' => 'text/csv; charset=utf-8',
            "Content-Encoding" => "UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function () use ($cols, $data) {
            echo "\xEF\xBB\xBF";
            
            $file = fopen('php://output', 'w');

            fputcsv($file, $cols);

            foreach ($data as $value) {
                fputcsv($file, array_values($value));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
