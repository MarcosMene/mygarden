<?php

namespace App\Controller;

use App\Entity\ReviewClient;
use App\Form\Users\ReviewClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewClientController extends AbstractController
{

    //CREATE REVIEW STORE
    #[Route('/review-store', name: 'review_store')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewStore = new ReviewClient();
        $form = $this->createForm(ReviewClientType::class, $reviewStore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reviewStore->setIsApproved(false);
            $reviewStore->setUser($this->getUser());
            $entityManager->persist($reviewStore);
            $entityManager->flush();

            //MESSAGE
            $this->addFlash('success', 'Thank you for your opinion. We are glad to know about you.');
            return $this->redirectToRoute('review_store');
        }
        return $this->render('pages/review_client.html.twig', [
            'formClient' => $form,
        ]);
    }
}
