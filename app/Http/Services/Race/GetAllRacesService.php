<?php

namespace App\Http\Services\Race;

use App\Http\Repositories\RaceRepository;

class GetAllRacesService
{
    private $raceRepository;

    public function __construct(RaceRepository $raceRepository)
    {
        $this->raceRepository = $raceRepository;
    }

    public function handle()
    {
        return $this->raceRepository->getAll();
    }
}
