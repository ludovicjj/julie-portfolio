<?php

namespace App\Builder;

use App\DTO\GalleryDTO;
use App\Entity\Gallery;
use App\Entity\Picture;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;

class GalleryBuilder
{
    private UploaderHelper $uploadHelper;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager
    )
    {
        $this->uploadHelper = $uploaderHelper;
        $this->entityManager = $entityManager;
    }

    public function build(GalleryDTO $dto): Gallery
    {
        $gallery = new Gallery();
        $gallery
            ->setTitle($dto->getTitle())
            ->setPublished($dto->getPublished())
            ->setTag($dto->getTag());

        $coverFileName = $this->uploadHelper->upload($dto->getCover());
        $gallery->setCover($coverFileName);

        foreach ($dto->getUploads() as $file) {
            $picture = new Picture();
            $picture->setName(uniqid());
            $fileName = $this->uploadHelper->upload($file);
            $picture->setPictureFileName($fileName);
            $gallery->addPicture($picture);
        }

        foreach ($dto->getPictures() as $picture) {
            $gallery->addPicture($picture);
        }

        $this->entityManager->persist($gallery);
        $this->entityManager->flush();

        return $gallery;
    }
}