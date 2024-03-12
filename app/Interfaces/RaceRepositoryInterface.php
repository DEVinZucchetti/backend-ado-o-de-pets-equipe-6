<?php

namespace App\Interfaces;

interface RaceRepositoryInterface {
    public function getAll();
    public function create(array $data);
}
