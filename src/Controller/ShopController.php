<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\Users\ReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ShopController extends AbstractController
{
  public function __construct(private EntityManagerInterface $em, private CsrfTokenManagerInterface $csrfTokenManager) {}

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
  public function list(string $slug, ProductRepository $productRepository, ReviewRepository $reviewRepository, Request $request): Response
  {
    $user = $this->getUser();

    $product = $productRepository->findOneBySlug($slug);
    if (!$product) {
      $this->addFlash('danger', 'This product doesn\'t exist.');
      return $this->redirectToRoute('shop'); // redirect to shop page
    }

    //FIND PRODUCT SUGGESTIONS
    $productSuggestions = $productRepository->findByisSuggestion($product);

    //FIND USER REVIEW
    $reviewUser = $reviewRepository->findOneBy(['product' => $product, 'user' => $this->getUser()]);

    //FIND OTHER USER REVIEWS
    $reviews = $reviewRepository->findApprovedReviewsByProduct($product->getId());

    //CREATE REVIEW
    $review = new Review();
    $formReview = $this->createForm(ReviewType::class, $review);
    $formReview->handleRequest($request);

    if ($formReview->isSubmitted()) {

      if ($formReview->isValid()) {

        $review->setIsApproved(false);
        $review->setProduct($product);
        $review->setUser($this->getUser());

        $this->em->persist($review);
        $this->em->flush();

        $this->addFlash('success', 'Your review is being uploaded. Thank you very much.');
        return $this->redirectToRoute('shop_product', ['slug' => $slug]);
      } else {
        $this->addFlash('error', 'Invalid CSRF token. Please try again.');
      }
    }

    return $this->render('products/show.html.twig', [
      'product' => $product,
      'slug' => $slug,
      'formReview' => $formReview,
      'user_reviewed' => $reviewRepository->findOneBy(['product' => $product, 'user' => $user]) !== null,
      'reviews' => $reviews,
      'productSuggestions'=>$productSuggestions,
      'reviewUser'=>$reviewUser
    ]);
  }
}
