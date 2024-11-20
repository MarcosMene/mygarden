<?php

namespace App\Controller\Admin;

use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailOrderController extends AbstractController
{
    // #[Route('/admin/detail/order/{id}', name: 'detail_order')]
    // public function detail($id, OrderRepository $orderRepository): Response
    // {
    //     $order = $orderRepository->find($id);

    //     return $this->render('admin/order_detail/order_detail.html.twig', [
    //         'order'=>$order
    //     ]);
    // }
}
