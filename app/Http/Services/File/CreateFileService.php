<?php

namespace App\Http\Services\File;

use App\Http\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;

class CreateFileService
{
    private $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function handle($folderPath, $file, $name)
    {
        $pathBucket = Storage::disk('s3')->put($folderPath, $file);
        $fullPathFile = Storage::disk('s3')->url($pathBucket);

        return $this->fileRepository->create([
            'name' => 'foto_' . $name,
            'size' => $file->getSize(),
            'mime' => $file->extension(),
            'url' => $fullPathFile
        ]);

    }
}
