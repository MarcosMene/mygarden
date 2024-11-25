<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;

class InvoiceController extends AbstractController
{
    /**
     * print invoice pdf for a connected user
     * connected user verifies his invoice with order details
     */
    #[Route('/account/invoice/print/{id_order}', name: 'user_invoice')]
    public function invoice_user(OrderRepository $orderRepository, $id_order): Response
    {
        $order = $orderRepository->findOneBy(['id' => $id_order]);

        //if order doesnt exist
        if (!$order) {
            $this->addFlash('danger', 'This order doesn\'t exist.');
            return $this->redirectToRoute('account');
        }

        //if connected user is the same of user's order
        if ($order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account');
        }

        //instantiate invoice
        $dompdf = new Dompdf();
        $html = $this->renderView('invoice/index.html.twig',[
            'order' => $order,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('invoice.pdf', [
            'Attachment' => false
        ]);
        exit();
    }

    /**
     * invoice pdf allow admin to see invoices from users.
     * 
     */
    #[Route('/admin/invoice/print/{id_order}', name: 'admin_invoice')]
    public function invoice_admin(OrderRepository $orderRepository, $id_order): Response
    {

        $order = $orderRepository->findOneBy(['id' => $id_order]);
        
        //if order doesnt exist
        if (!$order) {
            $this->addFlash('danger', 'This order doesn\'t exist.');
            return $this->redirectToRoute('admin');
        }

        $dompdf = new Dompdf();

        $html = $this->renderView('invoice/index.html.twig',[
            'order' => $order,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('invoice.pdf', [
            'Attachment' => false
        ]);
        exit();
    }
}
