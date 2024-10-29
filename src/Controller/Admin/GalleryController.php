<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class GalleryController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST PRODUCTS
    #[Route('/admin/list/galleries', name: 'show_galleries')]
    public function list(GalleryRepository $gallerieRepository): Response
    {
        $galleries = $gallerieRepository->findAll();
        return $this->render('admin/gallery/list_gallery.html.twig', [
            'galleries' => $galleries
        ]);
    }

    // CREATE GALLERY 
    #[Route('/admin/create/gallery', name: 'create_gallery')]
    public function create(Request $request, GalleryRepository $galleryRepository): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check if the gallery name already exists
            $existingGallery = $galleryRepository->findOneBy(['name' => $gallery->getName()]);

            if ($existingGallery) {
                $form->get('name')->addError(new FormError('This gallery name is already taken.'));
            } else {
                $this->entityManager->persist($gallery);
                $this->entityManager->flush();
                //message
                $this->addFlash('success', 'Your gallery was created with success');
                return $this->redirectToRoute('show_galleries');
            }
        }
        return $this->render('admin/gallery/gallery_form.html.twig', [
            'form' => $form
        ]);
    }

    // DETAIL GALLERY
    #[Route('/admin/detail/gallery/{id}', name: 'detail_gallery', requirements: ['id' => '[a-zA-Z0-9-_]+'])]
    public function detail(GalleryRepository $galleryRepository, $id): Response
    {
        $gallery = $galleryRepository->find($id);
        
        if (!$gallery) {
            $this->addFlash('danger', 'This gallery doesn\'t exist');
            return $this->redirectToRoute('show_galleries');
        }

        return $this->render('admin/gallery/detail_gallery.html.twig', [
            'gallery_detail' => $gallery
        ]);
    }

    //EDIT GALLERY
    #[Route('/admin/edit/gallery/{id}', name: 'edit_gallery', requirements: ['id' => '\d+'])]
    public function edit(GalleryRepository $galleryRepository, Request $request, $id): Response
    {
        $gallery = $galleryRepository->find($id);
        //IF CATEGORY DOESNT EXIST
        if (!$gallery) {
            $this->addFlash('danger', 'This gallery doesn\'t exist');
            return $this->redirectToRoute('show_galleries');
        }

        $form = $this->createForm(GalleryType::class, $gallery, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($gallery);
            $this->entityManager->flush();
            //message
            $this->addFlash('success', 'Your gallery was updated with success');
            return $this->redirectToRoute('show_galleries');
        }
        return $this->render('admin/gallery/gallery_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE GALLERY
    #[Route('/admin/delete/gallery/{id}', name: 'delete_gallery', requirements: ['id' => '\d+'])]
    public function delete(GalleryRepository $galleryRepository, Request $request, $id): Response
    {
        $gallery = $galleryRepository->find($id);
        if (!$gallery) {
            $this->addFlash('danger', 'This gallery doesn\'t exist');
            return $this->redirectToRoute('show_galleries');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken(
            'deleteGallery' . $id,
            $request->request->get('_token')
        );
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Gallery deleted succesfully');
            $this->entityManager->remove($gallery);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_galleries');
    }
}
