<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use App\Form\ReviewAdminType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ReviewProductController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST OF PRODUCT USER REVIEWS
    #[Route('/admin/list/review_products', name: 'show_review_products')]
    public function index(ReviewRepository $reviewRepository): Response
    {
        $reviewProducts = $reviewRepository->findAll();
        return $this->render('admin/review_product/list_review_product.html.twig', [
            'reviews' => $reviewProducts,
        ]);
    }

    //DETAIL REVIEW PRODUCT USER
    #[Route('/admin/detail/review_products/{id}', name: 'detail_review_products', requirements: ['id' => '\d+'])]
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
    #[Route('/admin/edit/review_products/{id}', name: 'edit_review_product', requirements: ['id' => '\d+'])]
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

        //security csrf
        $csrfToken = new CsrfToken('deleteReviewProduct' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that..');
        } else {
            $this->addFlash('success', 'Review deleted succesfully');
            $this->entityManager->remove($review_product);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_review_products');
    }
}
