<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopCategoryController extends AbstractController
{
  #[Route('/shop/products/{slug}', name: 'shop_category', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
  public function list(CategoryRepository $categoryRepository, string $slug): Response

  {
    $category = $categoryRepository->findOneBySlug($slug);

    return $this->render('pages/shop_category.html.twig', [
      'category' => $category
    ]);
  }
}
