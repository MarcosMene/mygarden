<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Classe\State;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderClientController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/list/order-client', name: 'show_order_client', methods: ['GET'])]
    public function index(Request $request, OrderRepository $orderRepository, PaginatorInterface $paginator): Response
    {
        $query = trim($request->query->get('query', ''));
        $page = $request->query->getInt('page', 1);

        $ordersQuery = $orderRepository->searchOrdersByUserOrOrderId($query); // Usa a nova função

        // PAGE RESULTS
        $orders = $paginator->paginate(
            $ordersQuery, // QUERY
            $page,        // CURRENT PAGE
            5            // LIMIT OF ITEMS PER PAGE
        );

        //IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/orders/_table_orders.html.twig', [
                'orders' => $orders,
            ]);
        }

        return $this->render('admin/order_client/list_orderClient.html.twig', [
            'orders' => $orders,
            'query' => $query
        ]);
    }

    #[Route('/admin/detail/order/{id}', name: 'detail_order')]
    public function detail($id, OrderRepository $orderRepository, Request $request): Response
    {
        $order = $orderRepository->find($id);

        if (!$order) {
            return $this->redirectToRoute('show_order_client');
        }

        //PROCESSING STATUS CHANGES IF STATUS EXIST
        if ($request->query->has('state')) {
            $state = $request->query->get('state');
            $this->changeState($order, $state);
        }

        return $this->render('admin/order_client/order_detail.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * function to change status of order
     *
     * @param [type] $state
     * @return void
     */
    public function changeState($order, $state)
    {
        //CHANGE STATE OF ORDER
        $order->setState($state);
        $this->entityManager->flush();

        //SHOW MESSAGE TO ADMIN
        $this->addFlash('success', 'Order status successfully changed.');

        //SEND EMAIL TO USER TO INFORM THE CHANGEMENT STATE OF HIS ORDER
        $mail = new Mail();
        $vars = [
            "firstname" => $order->getUser()->getFirstname(),
            "lastname" => $order->getUser()->getLastname(),
            "id_order" => $order->getId()
        ];
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname() . ' ' . $order->getUser()->getLastname(), State::STATE[$state]['email_subject'], State::STATE[$state]['email_template'], $vars);
    }
}
