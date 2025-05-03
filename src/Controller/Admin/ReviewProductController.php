<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use App\Form\Admin\ReviewAdminType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ReviewProductController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST PRODUCTS
    #[Route('/admin/list/review_products', name: 'show_review_products', methods: ['GET'])]
    public function list(ReviewRepository $reviewRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $query = trim($request->query->get('query', ''));
        $page = $request->query->getInt('page', 1);

        // SEARCH PRODUCTS
        $reviewProductQuery = $reviewRepository->searchProductReviews($query);

        // PAGE RESULTS
        $reviewProduct = $paginator->paginate(
            $reviewProductQuery, // QUERY
            $page,          // CURRENT PAGE
            5              // LIMIT OF ITEMS PER PAGE
        );

        // IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/review_products/_table_review_products.html.twig', [
                'reviews' => $reviewProduct,
            ]);
        }

        // IF IT'S NOT AJAX, IT RETURNS THE LIST OF ARTICLES
        return $this->render('admin/review_product/list_review_product.html.twig', [
            'reviews' => $reviewProduct,
            'query' => $query,
        ]);
    }

    //DETAIL REVIEW PRODUCT USER
    #[Route('/admin/detail/review_products/{id}', methods: ['GET'], name: 'detail_review_products', requirements: ['id' => '\d+'])]
    public function detail(ReviewRepository $reviewRepository, $id): Response
    {
        $review = $reviewRepository->find($id);
        //IF REPOSITORY DOESNT EXIST
        if (!$review) {
            $this->addFlash('danger', 'This review doesn\'t exist');
            return $this->redirectToRoute('show_reviews');
        }
        return $this->render('admin/review_product/detail_review_product.html.twig', [
            'review' => $review,
            'product' => $review->getProduct(),
        ]);
    }

    //EDIT PRODUCT USER REVIEWS
    #[Route('/admin/edit/review_products/{id}', methods: ['GET', 'POST'], name: 'edit_review_product', requirements: ['id' => '\d+'])]
    public function edit(int $id, ReviewRepository $reviewRepository, Request $request, Review $review): Response
    {
        $reviewProduct = $reviewRepository->find($id);

        //IF REVIEW DOESNT EXIST
        if (!$reviewProduct) {
            $this->addFlash('danger', 'This review doesn\'t exist');
            return $this->redirectToRoute('show_review_products');
        };

        $form = $this->createForm(ReviewAdminType::class, $reviewProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Review updated successfully');
            return $this->redirectToRoute('show_review_products');
        }
        return $this->render('admin/review_product/review_product_form.html.twig', [
            'form' => $form,
            'review' => $review,
            'product' => $review->getProduct(),
        ]);
    }

    //DELETE REVIEW PRODUCT
    #[Route('/admin/delete/review_product/{id}', name: 'delete_review_product', requirements: ['id' => '\d+'])]
    public function delete(ReviewRepository $reviewRepository, Request $request, $id): Response
    {
        $review_product = $reviewRepository->find($id);
        if (!$review_product) {
            $this->addFlash('danger', 'This review doesn\'t exist');
            return $this->redirectToRoute('show_review_products');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteReviewProduct' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Review deleted succesfully');
            $this->entityManager->remove($review_product);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_review_products');
    }
}
