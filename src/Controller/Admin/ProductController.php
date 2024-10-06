<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ProductController extends AbstractController
{
    // SHOW PRODUCT LIST
    #[Route('/admin/show/product', name: 'show_product')]
    public function show(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/product/list_product.html.twig');
    }

    // CREATE PRODUCT 
    #[Route('/admin/create/product', name: 'create_product')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            // CREATE SLUG FROM PRODUCT NAME 
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);
            $entityManager->persist($product);
            $entityManager->flush();
            //message
            $this->addFlash('success', 'Your product was created with success');
            return $this->redirectToRoute('show_product');
        }
        return $this->render('admin/product/create_product.html.twig', [
            'productForm' => $form
        ]);
    }
}
