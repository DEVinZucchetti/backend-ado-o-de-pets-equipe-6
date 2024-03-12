<?php

namespace App\Http\Services\Pet;

use App\Http\Repositories\PetRepository;


class CreateOnePetService
{
    private $petRepository;

    public function __construct(PetRepository $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    public function handle($data)
    {
        return $this->petRepository->createOne($data);
    }
}
