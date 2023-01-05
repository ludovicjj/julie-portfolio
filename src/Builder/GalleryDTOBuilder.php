<?php

namespace App\Builder;

use App\DTO\GalleryDTO;
use App\Repository\PictureRepository;
use App\Repository\TagRepository;
use stdClass;

class GalleryDTOBuilder
{
    private PictureRepository $pictureRepository;
    private TagRepository $tagRepository;

    public function __construct(
        PictureRepository $pictureRepository,
        TagRepository $tagRepository,
    )
    {
        $this->pictureRepository = $pictureRepository;
        $this->tagRepository = $tagRepository;
    }

    public function buildFromStdClass(stdClass $data): GalleryDTO
    {
        $dto = new GalleryDTO();

        //inputBag - title, published, pictures, tag
        $inputBag = $data->inputBag;
        $this->initTitle($inputBag, $dto);
        $this->initPublished($inputBag, $dto);
        $this->initPictures($inputBag, $dto);
        $this->initTag($inputBag, $dto);

        //fileBag - cover, uploads
        $fileBag = $data->fileBag;
        $this->initCover($fileBag, $dto);
        $this->initUploads($fileBag, $dto);

        return $dto;
    }

    private function initTitle(stdClass $inputBag, GalleryDTO $dto): void
    {
        if ($this->hasProperty($inputBag, 'title')) {
            $dto->setTitle($inputBag->title);
        }
    }

    private function initPublished(stdClass $inputBag, GalleryDTO $dto): void
    {
        if ($this->hasProperty($inputBag, 'published')) {
            $dto->setPublished($inputBag->published === '1');
        }
    }

    private function initPictures(stdClass $inputBag, GalleryDTO $dto): void
    {
        if ($this->hasProperty($inputBag, 'pictures')) {
            foreach ($inputBag->pictures as $imageId) {
                $picture = $this->pictureRepository->findOneBy(['id' => $imageId]);
                $picture && $dto->addPicture($picture);
            }
        }
    }

    private function initTag(stdClass $inputBag, GalleryDTO $dto): void
    {
        if ($this->hasProperty($inputBag, 'tag')) {
            $tagId = $inputBag->tag;
            if ($tagId) {
                $tag = $this->tagRepository->findOneBy(['id' => $tagId]);
                $tag && $dto->setTag($tag);
            }
        }
    }

    private function initCover(stdClass $fileBag, GalleryDTO $dto): void
    {
        if ($this->hasProperty($fileBag, 'cover')) {
            $cover = $fileBag->cover;
            $cover && $dto->setCover($cover);
        }
    }

    private function initUploads(stdClass $fileBag, GalleryDTO $dto): void
    {
        if ($this->hasProperty($fileBag, 'uploads')) {
            $dto->setUploads($fileBag->uploads);
        }
    }

    private function hasProperty(stdClass $object, string $property): bool
    {
        return property_exists($object, $property);
    }
}