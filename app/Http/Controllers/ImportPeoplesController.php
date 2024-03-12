<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Traits\HttpResponses;
use App\Traits\ManageFileCsv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Symfony\Component\HttpFoundation\Response;

class ImportPeoplesController extends Controller
{

    use HttpResponses;
    use ManageFileCsv;

    public function import(Request $request)
    {

        try {
            DB::beginTransaction();
            // Verifica se a solicitação contém um arquivo CSV
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $csvArray = $this->getArrayCsv($file, 4);

                foreach ($csvArray as $item) {

                    $peopleExist = People::query()
                        ->where('cpf', $item['cpf'])
                        ->orWhere('email', $item['email'])
                        ->first();

                    if (!$peopleExist) People::create($item);
                }

                DB::commit();

                return $this->response('Importado com sucesso', 201);
            } else {
                return $this->response("Arquivo ausente", 400);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
