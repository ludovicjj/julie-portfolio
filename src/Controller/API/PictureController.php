<?php

namespace App\Controller\API;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

class PictureController extends AbstractController
{
    #[Route('/api/pictures/search', name: 'api_picture_search')]
    public function search(Request $request, PictureRepository $pictureRepository, SerializerInterface $serializer)
    {
        $pictures = $pictureRepository->search($request->query->get('s', ''));
        $json = $serializer->serialize($pictures, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['galleries']]);
        return new JsonResponse($json, 200, [], true);
    }
}