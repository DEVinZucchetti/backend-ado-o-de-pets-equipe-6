<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Services\File\CreateFileService;
use App\Http\Services\Pet\CreateOnePetService;
use App\Http\Services\Pet\GetOnePetService;
use App\Http\Services\Pet\SendEmailWelcomeService;
use App\Http\Services\Pet\UpdateOnePetService;
use App\Mail\SendWelcomePet;
use Illuminate\Support\Str;

use App\Models\File;
use App\Models\People;
use App\Models\Pet;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

class PetController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        try {

            // pegar os dados que foram enviados via query params
            $filters = $request->query();

            // inicializa uma query
            $pets = Pet::query()
                ->select(
                    'id',
                    'pets.name as pet_name',
                    'pets.race_id',
                    'pets.specie_id',
                    'pets.size as size',
                    'pets.weight as weight',
                    'pets.age as age',
                    'pets.file_id'
                )
                #->with('race') // traz todas as colunas
                ->with(['race' => function ($query) {
                    $query->select('name', 'id');
                }])
                ->with('vaccines.professional.people')
                /*
                ->with(['vaccines.professional.people' => function ($query) {
                    $query->orderBy('created_at', 'desc'); // mostra exemplos
                }])
                */
                ->with('specie')
                ->with('file');

            // verifica se filtro
            if ($request->has('name') && !empty($filters['name'])) {
                $pets->where('name', 'ilike', '%' . $filters['name'] . '%');
            }

            if ($request->has('age') && !empty($filters['age'])) {
                $pets->where('age', $filters['age']);
            }

            if ($request->has('size') && !empty($filters['size'])) {
                $pets->where('size', $filters['size']);
            }

            if ($request->has('weight') && !empty($filters['weight'])) {
                $pets->where('weight', $filters['weight']);
            }

            if ($request->has('specie_id') && !empty($filters['specie_id'])) {
                $pets->where('specie_id', $filters['specie_id']);
            }

            // retorna o resultado
            $columnOrder = $request->has('order') && !empty($filters['order']) ?  $filters['order'] : 'name';

            return $pets->orderBy($columnOrder)->get();
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    private function sendWelcomeEmailToClient(Pet $pet)
    {
        if (!empty($pet->client_id)) {
            $people = People::find($pet->client_id);
            Mail::to($people->email, $people->name)
                ->send(new SendWelcomePet($pet->name, 'Henrique Douglas'));
        }
    }

    public function store(
        StorePetRequest $request,
        CreateFileService $createFileService,
        CreateOnePetService $createOnePetService,
        SendEmailWelcomeService $sendEmailWelcomeService
    ) {
        try {
            $file = $request->file('photo');
            $body =  $request->input();

            $file = $createFileService->handle('photos', $file, $body['name']);
            $pet = $createOnePetService->handle([...$body, 'file_id' => $file->id]);

            $sendEmailWelcomeService->handle($pet);

            return $pet;
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);

        $pet->delete();

        return $this->response('', Response::HTTP_NO_CONTENT);
    }

    public function show($id, GetOnePetService $getOnePetService)
    {
        $pet = $getOnePetService->handle($id);
        if (!$pet) return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);
        return $pet;
    }


    public function update($id, UpdatePetRequest $request, UpdateOnePetService $updateOnePetService)
    {
        try {
            $body = $request->all();
            $pet =  $updateOnePetService->handle($id, $body);
            return $pet;
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function upload(Request $request)
    {
        $createds = [];

        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {

                $description =  $request->input('description');

                $slugName = Str::of($description)->slug();
                $fileName = $slugName . '.' . $file->extension();

                $pathBucket = Storage::disk('s3')->put('documentos', $file);
                $fullPathFile = Storage::disk('s3')->url($pathBucket);

                $fileCreated = File::create(
                    [
                        'name' => $fileName,
                        'size' => $file->getSize(),
                        'mime' => $file->extension(),
                        'url' => $pathBucket
                    ]
                );

                array_push($createds, $fileCreated);
            }
        }

        return $createds;
    }
}
