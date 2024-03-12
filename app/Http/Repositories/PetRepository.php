<?php

namespace App\Http\Repositories;

use App\Interfaces\PetRepositoryInterface;
use App\Models\Pet;

class PetRepository implements PetRepositoryInterface {

    public function createOne(array $data) {
        return Pet::create($data);
    }

    public function getOne($id) {
        return Pet::find($id);
    }

    public function updateOne(Pet $pet, $data) {
        $pet->update($data);
        $pet->save();
        return $pet;
    }

}


