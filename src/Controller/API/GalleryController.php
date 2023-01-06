<?php

namespace App\Controller\API;

use App\Builder\GalleryBuilder;
use App\Builder\GalleryDTOBuilder;
use App\Exception\ValidatorException;
use App\Handler\Validation\ErrorsValidationHandler;
use App\Handler\Request\CreateGalleryRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GalleryController extends AbstractController
{
    /** @throws ValidatorException */
    #[Route('/api/gallery', name: 'api_gallery_create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        GalleryDTOBuilder $galleryDTOBuilder,
        GalleryBuilder $galleryBuilder
    ): Response
    {
        $data = CreateGalleryRequestHandler::handle($request);
        $galleryDTO = $galleryDTOBuilder->buildFromStdClass($data);
        $constraintList = $validator->validate($galleryDTO);
        ErrorsValidationHandler::handleErrors($constraintList);

        //$galleryBuilder->build($galleryDTO);

        return new JsonResponse(null, 201);
    }
}