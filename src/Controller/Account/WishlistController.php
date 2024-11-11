<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class WishlistController extends AbstractController
{
    private $entityManager;
    private $security;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security,  CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/account/wishlist', name: 'account_wishlist')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        if ($user) {
            return $this->render('account/wishlist/index.html.twig');
        }
    }

    #[Route('/account/wishlist/add/{id}', name: 'account_wishlist_add')]
    public function add(int $id, ProductRepository $productRepository, Request $request): Response
    {
        //get product from database
        $product = $productRepository->findOneById($id);

        if ($product) {
            $this->getUser()->addWishlist($product);
        }

        $this->entityManager->flush();
        $this->addFlash('success', 'The product has been added to your wishlist.');
        // back to last page visited 
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/account/wishlist/delete/{id}', name: 'account_wishlist_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, ProductRepository $productRepository, Request $request): Response
    {
        //get product from database
        $product = $productRepository->findOneById($id);

        if (!$product) {
            $this->addFlash('danger', 'This product doesn\'t exist');
            return $this->redirectToRoute('account_wishlist');
        }

        //security csrf
        $csrfToken = new CsrfToken('deleteWishlist' . $id, $request->request->get('_token'));

        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->getUser()->removeWishlist($product);
            $this->entityManager->flush();
            $this->addFlash('success', 'The product has been removed from your wishlist.');
        }
        // back to last page visited
        return $this->redirect($request->headers->get('referer'));
    }
}
