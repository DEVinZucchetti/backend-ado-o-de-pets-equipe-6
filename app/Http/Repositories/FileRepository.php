<?php

namespace App\Http\Repositories;
use App\Interfaces\FileRepositoryInterface;
use App\Models\File;

class FileRepository implements FileRepositoryInterface {

    public function create(array $data) {
        return File::create(
            $data
        );
    }

}


