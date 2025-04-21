<?php

namespace App\Controller\Admin;

use App\Entity\ReviewClient;
use App\Form\Admin\ReviewClientAdminType;
use App\Repository\ReviewClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ReviewClientAdminController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST REVIEW CLIENT STORE
    #[Route('/admin/list/review_store', name: 'show_store_admin', methods: ['GET'])]
    public function list(ReviewClientRepository $reviewClientRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $query = trim($request->query->get('query', ''));
        $page = $request->query->getInt('page', 1);

        // SEARCH PRODUCTS
        $reviewClientQuery = $reviewClientRepository->searchStoreReviews($query);

        // PAGE RESULTS
        $reviewClient = $paginator->paginate(
            $reviewClientQuery, // QUERY
            $page,          // CURRENT PAGE
            5              // LIMIT OF ITEMS PER PAGE
        );

        // IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/review_store/_table_review_store.html.twig', [
                'reviewClients' => $reviewClient,
            ]);
        }

        // IF IT'S NOT AJAX, IT RETURNS THE LIST OF ARTICLES
        return $this->render('admin/review_store/list_review_store.html.twig', [
            'reviewClients' => $reviewClient,
            'query' => $query,
        ]);
    }

    //DETAIL REVIEW CLIENT STORE
    #[Route('/admin/detail/review_store/{id}', name: 'detail_review_store', requirements: ['id' => '\d+'])]
    public function detail(ReviewClientRepository $reviewClientRepository, $id): Response
    {
        $review = $reviewClientRepository->find($id);

        //IF REPOSITORY DOESNT EXIST
        if (!$review) {
            $this->addFlash('danger', 'This review doesn\'t exist');
            return $this->redirectToRoute('show_store_admin');
        }
        return $this->render('admin/review_store/detail_review_store.html.twig', [
            'review' => $review,
        ]);
    }

    //EDIT REVIEW CLIENT STORE
    #[Route('/admin/edit/review_store/{id}', name: 'edit_review_store', requirements: ['id' => '\d+'])]
    public function edit(int $id, ReviewClientRepository $reviewClientRepository, Request $request, ReviewClient $reviewClient): Response
    {
        $reviewClient = $reviewClientRepository->find($id);

        //IF REVIEW DOESNT EXIST
        if (!$reviewClient) {
            $this->addFlash('danger', 'This review doesn\'t exist');
            return $this->redirectToRoute('show_store_admin');
        };

        $form = $this->createForm(ReviewClientAdminType::class, $reviewClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Review updated successfully');
            return $this->redirectToRoute('show_store_admin');
        }
        return $this->render('admin/review_store/review_store_form.html.twig', [
            'form' => $form,
            'review' => $reviewClient,
        ]);
    }

    //DELETE REVIEW CLIENT STORE
    #[Route('/admin/delete/review_client/{id}', name: 'delete_review_client', requirements: ['id' => '\d+'])]
    public function delete(ReviewClientRepository $reviewClientRepository, Request $request, $id): Response
    {
        $review_client = $reviewClientRepository->find($id);
        if (!$review_client) {
            $this->addFlash('danger', 'This review doesn\'t exist');
            return $this->redirectToRoute('show_review_clients');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteReviewClient' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Review client succesfully');
            $this->entityManager->remove($review_client);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_store_admin');
    }
}
