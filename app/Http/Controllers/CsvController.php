<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function readCsvFromRequest(Request $request)
    {
        // Verifica se a solicitação contém um arquivo CSV
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Lê o conteúdo do arquivo CSV
            $content = file_get_contents($file->getRealPath());

            // Converte o conteúdo CSV para uma matriz associativa
            $csvData = array_map('str_getcsv', explode("\n", $content));

            $headers = array_shift($csvData);

            $csvArray = [];
            foreach ($csvData as $row) {
                $csvArray[] = array_combine($headers, $row);
            }

            return $csvArray;
        } else {
            return response()->json(['error' => 'Arquivo CSV não encontrado na solicitação'], 400);
        }
    }
}
