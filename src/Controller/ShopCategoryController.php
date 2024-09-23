<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopCategoryController extends AbstractController
{
  #[Route('/shop/categories/{slug}', name: 'shop_categories', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
  public function show(string $slug): Response
  {
    return $this->render('pages/shop_category.html.twig');
  }
}
