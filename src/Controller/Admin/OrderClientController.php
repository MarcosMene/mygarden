<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Classe\State;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderClientController extends AbstractController
{
    private $entityManager; 

    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
        
    }



    #[Route('/admin/list/order-client', name: 'show_order_client')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAllOrderedCreatedDesc();

        return $this->render('admin/order_client/list_orderClient.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/admin/detail/order/{id}', name: 'detail_order')]
    public function detail($id, OrderRepository $orderRepository, Request $request ): Response
    {
        $order = $orderRepository->find($id);

        if(!$order){
            return $this->redirectToRoute('show_order_client');
        }

        //processing status changes if status exist
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
    public function changeState($order,$state)
    {


        //change state of order
        $order->setState($state);
        $this->entityManager->flush();
        
        //show message to admin
        $this->addFlash('success','Order status successfully changed.');

        //send email to user to inform the changement state of his order
        $mail = new Mail();
        $vars = [
            "firstname" => $order->getUser()->getFirstname(),
            "lastname" => $order->getUser()->getLastname(),
            "id_order"=>$order->getId()
        ];
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname() . ' ' . $order->getUser()->getLastname(), State::STATE[$state]['email_subject'], State::STATE[$state]['email_template'], $vars);
    }
}
