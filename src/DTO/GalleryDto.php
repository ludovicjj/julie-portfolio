<?php

namespace App\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class GalleryDto
{
    #[Assert\NotBlank(message: "Vous devez donner un titre à votre galerie.")]
    public ?string $title;

    #[Assert\NotBlank(message: "Vous devez choisir une image à la une.")]
    #[Assert\File(
        maxSize: '1024k',
        extensions: [
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        ],
        extensionsMessage: 'Seuls les fichiers JPG, PNG et GIF sont autorisés.',
    )]
    public ?UploadedFile $cover;

    public bool $published;

    #[Assert\All([
        new Assert\File(
            maxSize: '1024k',
            extensions: [
                'jpg' => 'image/jpeg',
//                'png' => 'image/png',
                'gif' => 'image/gif'
            ],
            extensionsMessage: 'Seuls les fichiers JPG, PNG et GIF sont autorisés.',
        )
    ])]
    public ?array $uploads;
    public ?ArrayCollection $images;

    public function __construct(
        ?string $title = null,
        ?UploadedFile $cover = null,
        bool $published = false,
        ?array $uploads = null,
        ?ArrayCollection $images = null
    )
    {
        $this->title = $title;
        $this->cover = $cover;
        $this->published = $published;
        $this->uploads = $uploads;
        $this->images = $images;
    }
}