<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
  #[Route('/shop', name: 'shop')]
  public function index(ProductRepository $productRepository): Response
  {
    $products = $productRepository->findAll();

    return $this->render(
      'pages/shop.html.twig',
      [
        'products' => $products
      ]
    );
  }

  #[Route('/shop/product/{slug}', name: 'shop_product', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
  public function show(string $slug, ProductRepository $productRepository): Response
  {

    $product = $productRepository->findOneBySlug($slug);
    if (!$product) {
      return $this->redirectToRoute('shop'); // redirect to shop page
    }

    return $this->render('products/show.html.twig', [
      'product' => $product,
      'slug' => $slug
    ]);
  }
}
