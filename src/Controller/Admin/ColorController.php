<?php

namespace App\Controller\Admin;

use App\Entity\ColorProduct;
use App\Form\ColorType;
use App\Repository\ColorProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ColorController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST COLOR
    #[Route('/admin/list/colors', name: 'show_colors')]
    public function index(ColorProductRepository $colorProductRepository): Response
    {
        $colors = $colorProductRepository->findAll();
        return $this->render('admin/color/list_color.html.twig', [
            'colors' => $colors
        ]);
    }

    //CREATE COLOR
    #[Route('/admin/create/color', name: 'create_color')]
    public function createColor(Request $request): Response
    {
        $color = new ColorProduct();
        $form = $this->createForm(ColorType::class, $color, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($color);
            $this->entityManager->flush();
            //message
            $this->addFlash('success', 'Your color was created with success');
            return $this->redirectToRoute('show_colors');
            // }
        }
        return $this->render('admin/color/color_form.html.twig', [
            'form' => $form
        ]);
    }

    //DETAIL COLOR
    #[Route('/admin/detail/color/{id}', name: 'detail_color', requirements: ['id' => '\d+'])]
    public function detail(ColorProductRepository $colorProductRepository, $id): Response
    {
        $color = $colorProductRepository->find($id);
        //IF CATEGORY DOESNT EXIST
        if (!$color) {
            $this->addFlash('danger', 'This color doesn\'t exist');
            return $this->redirectToRoute('show_colors');
        }
        return $this->render('admin/color/detail_color.html.twig', [
            'color' => $color
        ]);
    }

    //EDIT COLOR
    #[Route('/admin/edit/color/{id}', name: 'edit_color', requirements: ['id' => '\d+'])]
    public function edit(ColorProductRepository $colorProductRepository, Request $request, $id): Response
    {
        $color = $colorProductRepository->find($id);
        //IF CATEGORY DOESNT EXIST
        if (!$color) {
            $this->addFlash('danger', 'This color doesn\'t exist');
            return $this->redirectToRoute('show_colors');
        }

        $form = $this->createForm(ColorType::class, $color, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($color);
            $this->entityManager->flush();
            //message
            $this->addFlash('success', 'Your color was updated with success');
            return $this->redirectToRoute('show_colors');
        }
        return $this->render('admin/color/color_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE COLOR
    #[Route('/admin/delete/color/{id}', name: 'delete_color', requirements: ['id' => '\d+'])]
    public function delete(ColorProductRepository $colorProductRepository, Request $request, $id): Response
    {
        $color = $colorProductRepository->find($id);
        if (!$color) {
            $this->addFlash('danger', 'This color doesn\'t exist');
            return $this->redirectToRoute('show_colors');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken(
            'deleteColor' . $id,
            $request->request->get('_token')
        );
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Color deleted succesfully');
            $this->entityManager->remove($color);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_colors');
    }
}
