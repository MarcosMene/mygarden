<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CartController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //CART WITH PRODUCTS 
    #[Route('/my-cart/{reason}', name: 'cart', defaults:['reason'=>null])]
    public function index(Cart $cart, $reason): Response
    {
        if($reason == 'annulation'){
            $this->addFlash(
                'info',
                'Payment canceled: you can update your cart and your order'
            );
        }

        return $this->render('pages/cart.html.twig',[
            'cart' => $cart->getCart(),
            'totalWt'=>$cart->getTotalWt(),
            'totalNt'=>$cart->getTotal()
        ]);
    }

    //ADD PRODUCT TO CART
    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->findOneById($id);

        $cart->add($product);

        $this->addFlash('success', 'Product correctly added to your cart.');

        // back to last page visited 
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_from_cart')]
    public function decrease($id, Cart $cart, Request $request): Response
    {
        $cart->decrease($id);
        if (count($cart->getCart()) == 0) {
            //message
            $this->addFlash('danger', 'Your cart is empty');
        } else {
            $this->addFlash('success', 'The product has been deleted from your shopping cart');
        }
        return $this->redirectToRoute('cart');
    }

    //DELETE PRODUCT FROM CART
    #[Route('/cart/delete/{id}', name: 'delete_from_cart')]
    public function delete($id, Cart $cart, Request $request): Response
    {
        $csrfToken = new CsrfToken(
            'deleteFromCart' . $id,
            $request->request->get('_token')
        );
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have access to it.');
        } else {
            $cart->delete($id);
            $this->addFlash('success', 'The product has been deleted from your shopping cart');
        }
        // back to last page visited 
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/remove', name: 'remove_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        $this->addFlash('success', 'Your cart has been emptied');
        return $this->redirectToRoute('home');
    }
}
