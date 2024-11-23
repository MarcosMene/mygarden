<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function home(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findSuccessOrders($this->getUser());

        return $this->render('account/index.html.twig',[
            'orders' => $orders
        ]);
    }
}
