<?php

namespace App\Traits;

trait ManageFileCsv
{

    public function getArrayCsv($file, int $amountData, string $separator = ';',)
    {
        $contentFile = file_get_contents($file->getRealPath());

        // Converte o conteúdo CSV para uma matriz associativa

        // $csvData = array_map('str_getcsv', explode("\n", $contentFile)); -> importa com ,
        // importa com ;
        $csvData = array_map(function ($row) use ($separator) {
            return str_getcsv($row, $separator);
        }, explode("\n", $contentFile));

        // Pega as keys do array
        $headers = array_shift($csvData);

        // remover caracteres estranhos das keys
        $headerParse = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers);

        $csvArray = [];

        foreach ($csvData as $row) {
            if (count($row) === $amountData) {
                $csvArray[] = array_combine($headerParse, $row);
            }
        }

        return $csvArray;
    }
}
