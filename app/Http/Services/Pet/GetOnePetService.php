<?php

namespace App\Http\Services\Pet;

use App\Http\Repositories\PetRepository;

class GetOnePetService
{
    private $petRepository;

    public function __construct(PetRepository $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    public function handle($id)
    {
        return $this->petRepository->getOne($id);
    }
}
