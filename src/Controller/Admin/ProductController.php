<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ColorProductRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Handler\UploadHandler;

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
    #[Route('/admin/list/products', name: 'show_products', methods: ['GET'])]
    public function list(ProductRepository $productRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $query = trim($request->query->get('query', ''));
        $page = $request->query->getInt('page', 1);

        // SEARCH PRODCUTS
        $productsQuery = $productRepository->searchProducts($query);

        // PAGE RESULTS
        $products = $paginator->paginate(
            $productsQuery, // QUERY
            $page,          // CURRENT PAGE
            5              // LIMIT OF ITEMS PER PAGE
        );

        // IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/products/_table_products.html.twig', [
                'products' => $products,
            ]);
        }

        // IF IT'S NOT AJAX, IT RETURNS THE LIST OF ARTICLES
        return $this->render('admin/product/list_product.html.twig', [
            'products' => $products,
            'query' => $query,
        ]);
    }

    // CREATE PRODUCT 
    #[Route('/admin/create/product', name: 'create_product')]
    public function create(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, ColorProductRepository $colorProductRepository, UploadHandler $uploadHandler): Response
    {
        $colorCounts = $colorProductRepository->findAll();
        $categoryCounts = $categoryRepository->findAll();

        if (empty($colorCounts)) {
            $this->addFlash('danger', 'You must create at least one color before creating a product');
            return $this->redirectToRoute('create_color');
        }

        if (empty($categoryCounts)) {
            $this->addFlash('danger', 'You must create at least one category before creating a product');
            return $this->redirectToRoute('create_category');
        }

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product, ['is_edit' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setCreatedAt(new \DateTime());

            // CHECK IF THE PRODUCT NAME ALREADY EXISTS
            $existingProduct = $productRepository->findOneBy(['name' => $product->getName()]);

            if ($existingProduct) {
                $form->get('name')->addError(new FormError('This product name is already taken.'));
            } else {

                //GET INFO FROM FORM
                $product = $form->getData();

                //VERIFY IF A PRODUCT HAS PROMOTION
                $promotion = $product->getPromotion();

                if ($promotion > 0) {
                    $product->setIsPromotion(true);
                } else {
                    $product->setIsPromotion(false);
                }

                // CREATE SLUG FROM PRODUCT NAME 
                $slugger = new AsciiSlugger();
                $slug = $slugger->slug(strtolower($product->getName()));
                $product->setSlug($slug);


                //CHECK THAT THE FILE HAS BEEN SENT
                if ($product->getImageFile()) {
                    // SAVE THE IMAGE WITH VICHUPLOADERBUNDLE
                    $uploadHandler->upload($product, 'imageFile');
                }

                //  GETS THE NAME OF THE ORIGINAL FILE SAVED BY VICHUPLOADER
                $originalFilename = $product->getImageName();
                $originalPath = $this->getParameter('kernel.project_dir') . '/public/upload/products/' . $originalFilename;

                if ($originalFilename && file_exists($originalPath)) {
                    // NEW NAME FOR THE WEBP IMAGE
                    $newFilename = uniqid() . '.webp';
                    $destination = $this->getParameter('kernel.project_dir') . '/public/upload/products/' . $newFilename;

                    // CONVERT TO WEBP
                    $imagine = new Imagine();
                    $image = $imagine->open($originalPath);
                    $image->resize(new Box(400, 400))
                        ->save($destination, ['format' => 'webp', 'quality' => 85]);

                    //REMOVES THE ORIGINAL FILE AFTER CONVERSION
                    unlink($originalPath);

                    // UPDATES THE ENTITY WITH THE NEW WEBP FILE NAME
                    $product->setImageName($newFilename);
                }

                $this->entityManager->persist($product);
                $this->entityManager->flush();

                //MESSAGE
                $this->addFlash('success', 'Your product was created with success');
                return $this->redirectToRoute('show_products');
            }
        }
        return $this->render('admin/product/product_form.html.twig', [
            'form' => $form
        ]);
    }

    // DETAIL PRODUCT
    #[Route('/admin/detail/product/{slug}', name: 'detail_product', methods: ['GET'], requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
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
    #[Route('/admin/edit/product/{id}', name: 'edit_product', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, ProductRepository $productRepository, UploadHandler $uploadHandler, $id): Response
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
            //GET INFO FROM FORM
            $product = $form->getData();

            // CREATE SLUG FROM PRODUCT NAME 
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);

            //CHECK THAT THE FILE HAS BEEN SENT
            if ($product->getImageFile()) {
                // SAVE THE IMAGE WITH VICHUPLOADERBUNDLE
                $uploadHandler->upload($product, 'imageFile');
            }

            //  GETS THE NAME OF THE ORIGINAL FILE SAVED BY VICHUPLOADER
            $originalFilename = $product->getImageName();
            $originalPath = $this->getParameter('kernel.project_dir') . '/public/upload/products/' . $originalFilename;

            if ($originalFilename && file_exists($originalPath)) {
                // NEW NAME FOR THE WEBP IMAGE
                $newFilename = uniqid() . '.webp';
                $destination = $this->getParameter('kernel.project_dir') . '/public/upload/products/' . $newFilename;

                // CONVERT TO WEBP
                $imagine = new Imagine();
                $image = $imagine->open($originalPath);
                $image->resize(new Box(400, 400))
                    ->save($destination, ['format' => 'webp', 'quality' => 85]);

                //REMOVES THE ORIGINAL FILE AFTER CONVERSION
                unlink($originalPath);

                // UPDATES THE ENTITY WITH THE NEW WEBP FILE NAME
                $product->setImageName($newFilename);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Product updated succesfully');
            return $this->redirectToRoute('show_products');
        }
        return $this->render('admin/product/product_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE PRODUCT
    #[Route('/admin/delete/product/{id}', name: 'delete_product', requirements: ['id' => '\d+'])]
    public function delete(ProductRepository $productRepository, Request $request, $id): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            $this->addFlash('danger', 'This product doesn\'t exist');
            return $this->redirectToRoute('show_products');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteProduct' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Product deleted succesfully');
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_products');
    }
}
