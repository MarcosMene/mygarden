<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ColorProductRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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
    public function create(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, ColorProductRepository $colorProductRepository): Response
    {
        $colorCounts = $colorProductRepository->findAll();
        $categoryCounts = $categoryRepository->findAll();

        if (empty($colorCounts)) {
            $this->addFlash('error', 'You must create at least one color before creating a product');
            return $this->redirectToRoute('create_color');
        }

        if (empty($categoryCounts)) {
            $this->addFlash('error', 'You must create at least one category before creating a product');
            return $this->redirectToRoute('create_category');
        } else {
            $product = new Product();
            $form = $this->createForm(ProductType::class, $product, ['is_edit' => false]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // Check if the product name already exists
                $existingProduct = $productRepository->findOneBy(['name' => $product->getName()]);

                if ($existingProduct) {
                    $form->get('name')->addError(new FormError('This product name is already taken.'));
                } else {

                    $product = $form->getData();

                    // CREATE SLUG FROM PRODUCT NAME 
                    $slugger = new AsciiSlugger();
                    $slug = $slugger->slug(strtolower($product->getName()));
                    $product->setSlug($slug);
                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
                    //message
                    $this->addFlash('success', 'Your product was created with success');
                    return $this->redirectToRoute('show_products');
                }
            }
            return $this->render('admin/product/product_form.html.twig', [
                'form' => $form
            ]);
        }
    }

    // DETAIL PRODUCT
    #[Route('/admin/detail/product/{slug}', name: 'detail_product', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
    public function detail(ProductRepository $productRepository, $slug): Response
    {
        $product = $productRepository->findOneBySlug($slug);
        if (!$product) {
            $this->addFlash('danger', 'This product doesn\'t exist');
            return $this->redirectToRoute('show_products');
        }

        return $this->render('admin/product/detail_product.html.twig', [
            'product_detail' => $product
        ]);
    }

    //EDIT PRODUCT
    #[Route('/admin/edit/product/{id}', name: 'edit_product', requirements: ['id' => '\d+'])]
    public function edit(Request $request, ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);

        //IF PRODUCT DOESNT EXIST
        if (!$product) {
            $this->addFlash('danger', 'This product doesn\'t exist');
            return $this->redirectToRoute('show_products');
        }

        $form = $this->createForm(ProductType::class, $product, ['is_edit' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            // CREATE SLUG FROM PRODUCT NAME 
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
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
        if (!$product) {
            $this->addFlash('danger', 'This product doesn\'t exist');
            return $this->redirectToRoute('show_products');
        }

        //security csrf
        $csrfToken = new CsrfToken('deleteProduct' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have access to it.');
        } else {
            $this->addFlash('success', 'Product deleted succesfully');
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_products');
    }
}
