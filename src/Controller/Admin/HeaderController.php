<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use App\Form\Admin\HeaderType;
use App\Repository\HeaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Handler\UploadHandler;

class HeaderController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST HEADERS
    #[Route('/admin/list/headers', name: 'show_headers', methods: ['GET'])]
    public function list(HeaderRepository $headerRepository): Response
    {
        $headers = $headerRepository->findAll();
        return $this->render('admin/header/list_header.html.twig', [
            'headers' => $headers
        ]);
    }

    // CREATE HEADER 
    #[Route('/admin/create/header', name: 'create_header')]
    public function create(HeaderRepository $headerRepository, Request $request, UploadHandler $uploadHandler): Response
    {

        $totalElementsCarousel = $headerRepository->findAll();

        //MAXIMUM 4 ELEMENTS ON CAROUSEL
        if (count($totalElementsCarousel) == 4) {
            $this->addFlash('danger', 'You have reached the maximum number of elements.');
            return $this->redirectToRoute('show_headers');
        }

        $header = new Header();
        $form = $this->createForm(HeaderType::class, $header, ['is_edit' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //GET INFO FROM FORM
            $header = $form->getData();

            //CHECK THAT THE FILE HAS BEEN SENT
            if ($header->getImageFile()) {
                // SAVE THE IMAGE WITH VICHUPLOADERBUNDLE
                $uploadHandler->upload($header, 'imageFile');
            }

            //  GETS THE NAME OF THE ORIGINAL FILE SAVED BY VICHUPLOADER
            $originalFilename = $header->getImageName();
            $originalPath = $this->getParameter('kernel.project_dir') . '/public/upload/headers/' . $originalFilename;

            if ($originalFilename && file_exists($originalPath)) {
                // NEW NAME FOR THE WEBP IMAGE
                $newFilename = uniqid() . '.webp';
                $destination = $this->getParameter('kernel.project_dir') . '/public/upload/headers/' . $newFilename;

                // CONVERT TO WEBP
                $imagine = new Imagine();
                $image = $imagine->open($originalPath);
                $image->resize(new Box(540, 540))
                    ->save($destination, ['format' => 'webp', 'quality' => 85]);

                //REMOVES THE ORIGINAL FILE AFTER CONVERSION
                unlink($originalPath);

                // UPDATES THE ENTITY WITH THE NEW WEBP FILE NAME
                $header->setImageName($newFilename);
            }

            //SET URL FROM FORM
            $urlHeader = "/shop/products/" . $header->getCategoryProduct()->getSlug();
            $header->setButtonLink($urlHeader);

            $this->entityManager->persist($header);
            $this->entityManager->flush();
            //MESSAGE
            $this->addFlash('success', 'Your header was created with success');
            return $this->redirectToRoute('show_headers');
        }
        return $this->render('admin/header/header_form.html.twig', [
            'form' => $form
        ]);
    }

    // DETAIL HEADER
    #[Route('/admin/detail/header/{id}', name: 'detail_header', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function detail(HeaderRepository $headerRepository, $id): Response
    {
        $header = $headerRepository->find($id);
        if (!$header) {
            $this->addFlash('danger', 'This header doesn\'t exist');
            return $this->redirectToRoute('show_headers');
        }
        return $this->render('admin/header/detail_header.html.twig', [
            'header_detail' => $header
        ]);
    }

    //EDIT HEADER
    #[Route('/admin/edit/header/{id}', name: 'edit_header', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, HeaderRepository $headerRepository, $id): Response
    {
        $header = $headerRepository->find($id);

        //IF HEADER DOESNT EXIST
        if (!$header) {
            $this->addFlash('danger', 'This header doesn\'t exist');
            return $this->redirectToRoute('show_headers');
        }

        $form = $this->createForm(HeaderType::class, $header, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //GET INFO FROM FORM
            $header = $form->getData();

            //GET VALUE FROM BUTTON TITLE TO PUT ON URL
            $buttonTitle = $header->getButtonTitle();

            if (str_contains($buttonTitle, 'contact')) {
                //SET URL FROM FORM
                $urlHeader = "/contact";
                $header->setButtonLink($urlHeader);
            } else {
                //SET URL FROM FORM
                $urlHeader = "/shop/products/" . $header->getCategoryProduct()->getSlug();
                $header->setButtonLink($urlHeader);
            }

            $this->entityManager->flush();
            $this->addFlash('success', 'Header updated succesfully');
            return $this->redirectToRoute('show_headers');
        }
        return $this->render('admin/header/header_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE HEADER
    #[Route('/admin/delete/header/{id}', name: 'delete_header', requirements: ['id' => '\d+'])]
    public function delete(HeaderRepository $headerRepository, Request $request, $id): Response
    {
        $header = $headerRepository->find($id);
        if (!$header) {
            $this->addFlash('danger', 'This header doesn\'t exist');
            return $this->redirectToRoute('show_headers');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteHeader' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Header deleted succesfully');
            $this->entityManager->remove($header);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_headers');
    }
}
