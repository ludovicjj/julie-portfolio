<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Picture;
use App\Repository\GalleryRepository;
use App\Service\UploaderHelper;
use App\Type\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/admin/gallery', name: "app_gallery")]
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('gallery/gallery_index.html.twig', [
            "galleries" => $galleryRepository->findAll()
        ]);
    }

    #[Route('/admin/gallery/new', name: "app_gallery_new")]
    public function create(Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GalleryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('images')->getData();
            $collections = $form->get('collection')->getData();

            /** @var Gallery $gallery */
            $gallery = $form->getData();


            foreach ($files as $file) {
                $picture = new Picture();
                $picture->setName(uniqid());
                $fileName = $uploaderHelper->upload($file);
                $picture->setPictureFileName($fileName);

                $gallery->addPicture($picture);
            }

            foreach ($collections as $collection) {
                $gallery->addPicture($collection);
            }

            $entityManager->persist($gallery);
            $entityManager->flush();

            return $this->redirectToRoute("app_gallery");
        }

        return $this->render('gallery/gallery_add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/gallery/{id}', name: "app_gallery_show")]
    public function show(GalleryRepository $galleryRepository, int $id): Response
    {
        $gallery = $galleryRepository->findOneByIdAndJoinToPicture($id);

        if (!$gallery) {
            dd("NotFoundHttpException");
        }
        return $this->render('gallery/gallery_show.html.twig', [
            "gallery" => $gallery
        ]);
    }
}