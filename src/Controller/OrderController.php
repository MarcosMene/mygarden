<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    /**
     * choose delivery address and carrier company
     */
    #[Route('/order/delivery', name: 'order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        //IF ADDRESSES ARE EMPTY GO TO CREATE ADDRESS PAGE
        if (count($addresses) == 0) {
            $this->addFlash('danger', 'You need to create new address.');
            return $this->redirectToRoute('account_create_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('order_summary') //if form validate, send user to summary page
        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form,
        ]);
    }

    /**
     * summary of user's order
     * put order on database
     * payment preparation to Stripe
     * @param int $id
     * 
     */
    #[Route('/order/summary', name: 'order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManagerInterface): Response
    {

        //only access this page with method POST
        if ($request->getMethod() != 'POST') {
            $this->addFlash('danger', 'You can\'t access this page.');
            return $this->redirectToRoute('cart');
        }

        $products = $cart->getCart();

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //create string address
            $addressObj = $form->get('addresses')->getData();
            $address = $addressObj->getFirstname() .' '. $addressObj->getLastname() . '<br/>';
            $address .= $addressObj->getAddress() . '<br/>';
            $address .= $addressObj->getPostal() . ' ' . $addressObj->getCity() . '<br/>';
            $address .= 'Phone:'.$addressObj->getPhone();

            //save data from form to database
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \Datetime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());

            //get address to put on delivery
            $order->setDelivery($address);

            //create new object order detail
            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getImageName());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductPromotion($product['object']->getPromotion());
                $orderDetail->setProductQuantity($product['quantity']);
                $order->addOrderDetail($orderDetail);
            }
            $entityManagerInterface->persist($order);
            $entityManagerInterface->flush();
        }

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart'=>$products,
            'order'=>$order,
            'totalWt'=>$cart->getTotalWt()
        ]);
    }
}
