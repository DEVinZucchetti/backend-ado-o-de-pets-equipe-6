<?php

namespace App\Interfaces;

use App\Models\Pet;

interface PetRepositoryInterface {

    public function createOne(array $data);
    public function getOne($id);
    public function updateOne(Pet $pet, $id);
}
