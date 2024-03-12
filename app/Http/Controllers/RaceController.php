<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreRaceRequest;

use App\Http\Services\Race\CreateRaceService;
use App\Http\Services\Race\GetAllRacesService;
use App\Traits\HttpResponses;

use Symfony\Component\HttpFoundation\Response;

use Exception;


class RaceController extends Controller
{
    use HttpResponses;

    // Lista todos ou parcialmente os dados de um recurso
    public function index(GetAllRacesService $getAllRacesService)
    {
        $races = $getAllRacesService->handle();
        return $races;
    }

    public function store(StoreRaceRequest $request, CreateRaceService $createRaceService)
    {
        try {
            $body = $request->all();
            $race = $createRaceService->handle($body);
            return $race;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
