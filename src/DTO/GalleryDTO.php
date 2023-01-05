<?php

namespace App\DTO;

use App\Entity\Picture;
use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class GalleryDTO
{
    #[Assert\NotBlank(message: "Vous devez donner un titre à votre galerie.")]
    private ?string $title = null;

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
    private ?UploadedFile $cover = null;

    #[Assert\Choice([true, false], message: 'Le choix sélectionné est invalide.')]
    private bool $published;

    #[Assert\All([
        new Assert\File(
            maxSize: '1024k',
            extensions: [
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif'
            ],
            extensionsMessage: 'Seuls les fichiers JPG, PNG et GIF sont autorisés.',
        )
    ])]
    private ?array $uploads = null;

    private ArrayCollection $pictures;

    private ?Tag $tag = null;

    public function __construct()
    {
        $this->published = false;
        $this->pictures = new ArrayCollection();
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setCover(UploadedFile $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCover(): ?UploadedFile
    {
        return $this->cover;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getPublished(): bool
    {
        return $this->published;
    }

    public function setUploads(array $uploads): self
    {
        $this->uploads = $uploads;

        return $this;
    }

    public function getUploads(): ?array
    {
        return $this->uploads;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
        }

        return $this;
    }

    public function getPictures(): ArrayCollection
    {
        return $this->pictures;
    }
}