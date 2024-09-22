<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
  #[Route('/shop', name: 'shop')]
  public function index(): Response
  {
    return $this->render('pages/shop.html.twig');
  }


  #[Route('/shop/product/{slug}', name: 'shop_product', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
  public function show(string $slug): Response
  {
    return $this->render('products/show.html.twig');
  }
}
