<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/order/payment/{id_order}', name: 'payment')]
    public function index($id_order, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        //GET THE ORDER LINKED TO THE USER - security to avoid user put any number on URL
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);
        if (!$order) {
            $this->addFlash('danger', 'This order doesn\'t exist.');
            return $this->redirectToRoute('home');
        }

        $products_for_stripe = [];

        //ONLY PRODUCTS
        foreach ($order->getOrderDetails() as $product) {

            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'usd',
                   'unit_amount' => number_format($product->getProductPriceWt(), 2, '', ''), //stripe save number without comma ex. 3223 and not 32,23
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [
                            $_ENV['DOMAIN'] . '/upload/products/' . $product->getProductIllustration()
                        ]
                    ],
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }

        //FOR CARRIER
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''), //stripe save number without comma ex. 3223 and not 32,23
                'product_data' => [
                    'name' => 'Carrier: ' . $order->getCarrierName(),
                ],
            ],
            'quantity' => 1,
        ];


        //stripe session
        $checkout_session = Session::create([
            //GET USER EMAIL TO PUT ON STRIPE FORM
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $products_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/order/thank-you/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/my-cart/annulation',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }

    #[Route('/order/thank-you/{stripe_session_id}', name: 'payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManager, Cart $cart): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            $this->addFlash('danger', 'This order doesn\'t exist.');
            return $this->redirectToRoute('home');
        }

        //change state if order equal 1
        if ($order->getState() == 1) {

            //change state of order to paid
            $order->setState(2);

            //empty cart
            $cart->remove();

            $entityManager->flush();
        }

        return $this->render('payment/success.html.twig', [
            'order' => $order
        ]);
    }
}
