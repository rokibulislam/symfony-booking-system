<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    private $uploadsPath;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function getPublicPath($path): string
    {
        return $this->uploadsPath. '/' .$path;
    }


    public function uploadImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }
}