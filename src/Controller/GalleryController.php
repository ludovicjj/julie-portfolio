<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Repository\GalleryRepository;
use App\Service\UploaderHelper;
use App\Type\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/admin/gallery', name: "app_gallery")]
    public function index(GalleryRepository $galleryRepository): Response
    {
        $galleries = $galleryRepository->getGalleriesJoinThumbnail();

        return $this->render('admin/gallery/gallery_index.html.twig', [
            "galleries" => $galleries
        ]);
    }

    #[Route('/admin/gallery/new', name: "app_gallery_new")]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery, [
//            'action' => $this->generateUrl('api_gallery_create'),
        ])->handleRequest($request);

        if ($form->isSubmitted()) {
            $gallery = $form->getData();
            $uploadedPicture = $form->get('uploads')->getData();

            foreach ($uploadedPicture as $picture) {
                $gallery->addPicture($picture);
            }

            $entityManager->persist($gallery);
            $entityManager->flush();
        }

        return $this->render('admin/gallery/gallery_add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/gallery/update/{id}', name: "app_gallery_update")]
    public function update(
        GalleryRepository $galleryRepository,
        EntityManagerInterface $entityManager,
        UploaderHelper $uploaderHelper,
        int $id,
        Request $request
    ): Response
    {
        $gallery = $galleryRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(GalleryType::class, $gallery, [
//            'action' => $this->generateUrl('api_gallery_create'),
        ])->handleRequest($request);
        if ($form->isSubmitted()) {
            dd($form->getData());
        }
        return $this->render('admin/gallery/gallery_add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/gallery/{id}', name: "app_gallery_show", methods: ['GET'])]
    public function show(GalleryRepository $galleryRepository, int $id): Response
    {
        $gallery = $galleryRepository->getGalleryLeftJoinPicture($id);

        if (!$gallery) {
            throw new NotFoundHttpException();
        }
        return $this->render('admin/gallery/gallery_show.html.twig', [
            "gallery" => $gallery
        ]);
    }

    #[Route('/api/admin/gallery/{id}', name: "app_gallery_delete", methods: ['DELETE'])]
    public function delete(
        int $id,
        GalleryRepository $galleryRepository
    ): Response
    {
        $gallery = $galleryRepository->findOneBy(['id' => $id]);
        if (!$gallery) {
            return new JsonResponse(null, 404);
        }

        $galleryRepository->remove($gallery, true);
        return new JsonResponse(null, 204);
    }

    #[Route('/api/gallery/{gallery_id}/picture/{picture_id}', name: "api_gallery_picture_delete", methods: ['DELETE'])]
    public function deletePicture(
        int $gallery_id,
        int $picture_id,
        GalleryRepository $galleryRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $gallery = $galleryRepository->getGalleryInnerJoinPicture($gallery_id, $picture_id);

        if (!$gallery || empty($gallery->getPictures())) {
            return new JsonResponse(null, 404);
        }

        foreach ($gallery->getPictures() as $picture) {
            $gallery->removePicture($picture);
        }
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}