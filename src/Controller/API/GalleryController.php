<?php

namespace App\Controller\API;

use App\DTO\GalleryDto;
use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GalleryController extends AbstractController
{
    #[Route('/api/gallery', name: 'api_gallery_add', methods: ['POST'])]
    public function add(Request $request, PictureRepository $pictureRepository, ValidatorInterface $validator)
    {
        $formData = $request->request->all();
        $dto = new GalleryDto();

        if (isset($formData['gallery'])) {
            $galleryData = $formData['gallery'];

            $title = $galleryData['title'] ?? "";
            $published = $galleryData['published'] ?? false;
            $images = $galleryData['images'] ?? [];

            $pictures = new ArrayCollection();
            foreach ($images as $imageId) {
                $picture = $pictureRepository->findOneBy(['id' => $imageId]);
                if ($picture) {
                    $pictures->add($picture);
                }
            }

            // hydrate DTO (title, published & images)
            $dto->title = $title;
            $dto->published = $published === '1';
            $dto->images = $pictures;
        }

        // Les fichiers images
        $filesData = $request->files->all();

        if (isset($filesData['gallery'])) {
            $galleryFiles = $filesData['gallery'];

            $cover = $galleryFiles['cover'] ?? null;
            $uploads = $galleryFiles['uploads'] ?? [];

            // hydrate DTO (cover & uploads)
            $dto->cover = $cover;
            $dto->uploads = $uploads;
        }

        $constraintList = $validator->validate($dto);
        if (count($constraintList) > 0) {
            $errors = [];
            foreach ($constraintList as $constraint) {
                $errors[$constraint->getPropertyPath()][] = $constraint->getMessage();
            }
            return new JsonResponse($errors, 422);
        }
        return new JsonResponse('Tout est ok', 201);
    }
}