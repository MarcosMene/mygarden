<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/account/order/{id_order}', name: 'account_order')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {

        //find the order and only user order
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            $this->addFlash('danger', 'This order doesn\'t exist.');
            return $this->redirectToRoute('home');
        }

        return $this->render('account/order/index.html.twig', [
            'order' => $order,
        ]);
    }
}
