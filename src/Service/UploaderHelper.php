<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploaderHelper
{
    private SluggerInterface $slugger;
    private string $pathDirectory;

    public function __construct(SluggerInterface $slugger, string $pathDirectory)
    {
        $this->slugger = $slugger;
        $this->pathDirectory = $pathDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getPathDirectory(),
                $newFilename
            );
        } catch (FileException $e) {

        }

        return $newFilename;
    }

    public function getPathDirectory(): string
    {
        return $this->pathDirectory;
    }
}