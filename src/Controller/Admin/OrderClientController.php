<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderClientController extends AbstractController
{
    #[Route('/admin/list/order-client', name: 'show_order_client')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAllOrderedCreatedDesc();

        return $this->render('admin/order_client/list_orderClient.html.twig', [
            'orders' => $orders,
            ]);
    }

    #[Route('/admin/detail/order/{id}', name: 'detail_order')]
    public function detail($id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);

        return $this->render('admin/order_client/order_detail.html.twig', [
            'order'=>$order
        ]);
    }
}
