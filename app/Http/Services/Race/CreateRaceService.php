<?php

namespace App\Http\Services\Race;

use App\Http\Repositories\RaceRepository;

class CreateRaceService
{
    private $raceRepository;

    public function __construct(RaceRepository $raceRepository)
    {
        $this->raceRepository = $raceRepository;
    }

    public function handle(array $data)
    {
        return $this->raceRepository->create($data);
    }
}
