<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ProductController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST PRODUCTS
    #[Route('/admin/show/products', name: 'show_products')]
    public function show(ProductRepository $productRepository): Response

    {
        $products = $productRepository->findAll();
        return $this->render('admin/product/list_product.html.twig', [
            'products' => $products
        ]);
    }

    // CREATE PRODUCT 
    #[Route('/admin/create/product', name: 'create_product')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, ['is_edit' => false]);
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
            return $this->redirectToRoute('show_products');
        }
        return $this->render('admin/product/product_form.html.twig', [
            'form' => $form
        ]);
    }

    // DETAIL PRODUCT
    #[Route('/admin/detail/product/{slug}', name: 'detail_product', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
    public function detail(ProductRepository $productRepository, $slug): Response

    {
        $product = $productRepository->findOneBySlug($slug);
        return $this->render('admin/product/detail_product.html.twig', [
            'product_detail' => $product
        ]);
    }

    //MODIFY PRODUCT
    #[Route('/admin/modify/product/{id}', name: 'modify_product', requirements: ['id' => '\d+'])]
    public function modify(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product, ['is_edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            // CREATE SLUG FROM PRODUCT NAME 
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Product updated succesfully');
            return $this->redirectToRoute('show_products');
        }
        return $this->render('admin/product/product_form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/delete/product/{id}', name: 'delete_product', requirements: ['id' => '\d+'])]
    public function delete(ProductRepository $productRepository, Request $request, $id): Response
    {
        $product = $productRepository->find($id);

        //security csrf
        $csrfToken = new CsrfToken('deleteProduct' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have access to it.');
        } else {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
            $this->addFlash('success', 'Product deleted succesfully');
        }
        return $this->redirectToRoute('show_products');
    }
}
