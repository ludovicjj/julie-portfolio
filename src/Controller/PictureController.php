<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Service\UploaderHelper;
use App\Type\PictureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;

class PictureController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin/pictures', name: "app_picture")]
    public function pictures(): Response
    {
        $pictureRepository = $this->entityManager->getRepository(Picture::class);
        $pictures = $pictureRepository->findAll();

        return $this->render('admin/picture.html.twig', [
            'pictures' => $pictures
        ]);
    }

    #[Route('/admin/pictures/new', name: "app_picture_new")]
    public function addPicture(
        Request $request,
        UploaderHelper $uploaderHelper
    ): Response {

        $form = $this->createForm(PictureType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Picture $picture */
            $picture = $form->getData();

            /** @var UploadedFile $file */
            $file = $form->get('pictureFile')->getData();

            if ($file) {
                $pictureFileName = $uploaderHelper->upload($file);
                $picture->setPictureFileName($pictureFileName);
            }

            $this->entityManager->persist($picture);
            $this->entityManager->flush();

            $this->addFlash('success', 'One picture have been added with success !');
            return $this->redirectToRoute("app_picture");

        }

        return $this->render('admin/picture_new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/api/admin/pictures/{id}', name: "app_picture_delete", methods: ['DELETE'])]
    public function deletePicture(int $id)
    {
        $pictureRepository = $this->entityManager->getRepository(Picture::class);
        $picture = $pictureRepository->findOneBy(['id' => $id]);

        if (!$picture) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }
        // remove file on disc

//        $this->entityManager->remove($picture);
//        $this->entityManager->flush();
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/admin/pictures', name: "api_picture", methods: ['GET'])]
    public function getPictures(SerializerInterface $serializer)
    {
        $pictureRepository = $this->entityManager->getRepository(Picture::class);
        $pictures = $pictureRepository->findAll();

        $json = $serializer->serialize($pictures, 'json');

        return new JsonResponse($json, 200, [], true);
    }
}