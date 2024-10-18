<?php

namespace App\Controller;

use App\Entity\Carrier;
use App\Form\CarrierType;
use App\Repository\CarrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CarrierController extends AbstractController
{

    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST CARRIERS
    #[Route('/admin/show/carriers', name: 'show_carriers')]
    public function show(CarrierRepository $carrierRepository): Response
    {
        $carriers = $carrierRepository->findAll();
        return $this->render('admin/carrier/list_carrier.html.twig', [
            'carriers' => $carriers
        ]);
    }


    // CREATE CARRIER 
    #[Route('/admin/create/carrier', name: 'create_carrier')]
    public function create(Request $request, CarrierRepository $carrierRepository): Response
    {

        $carrier = new Carrier();
        $form = $this->createForm(CarrierType::class, $carrier, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check if the carrier name already exists
            $existingCarrier = $carrierRepository->findOneBy(['name' => $carrier->getName()]);

            if ($existingCarrier) {
                $form->get('name')->addError(new FormError('This carrier name is already taken.'));
            } else {
                $this->entityManager->persist($carrier);
                $this->entityManager->flush();
                //message
                $this->addFlash('success', 'Your carrier was created with success');
                return $this->redirectToRoute('show_carriers');
            }
        }
        return $this->render('admin/carrier/carrier_form.html.twig', [
            'form' => $form
        ]);
    }

    //DETAIL COLOR
    #[Route('/admin/detail/carrier/{id}', name: 'detail_carrier', requirements: ['id' => '\d+'])]
    public function detail(CarrierRepository $carrierRepository, $id): Response
    {
        $carrier = $carrierRepository->find($id);
        //IF CARRIER DOESNT EXIST
        if (!$carrier) {
            $this->addFlash('danger', 'This carrier doesn\'t exist');
            return $this->redirectToRoute('show_carriers');
        }
        return $this->render('admin/carrier/detail_carrier.html.twig', [
            'carrier' => $carrier
        ]);
    }

    //EDIT COLOR
    #[Route('/admin/edit/carrier/{id}', name: 'edit_carrier', requirements: ['id' => '\d+'])]
    public function edit(CarrierRepository $carrierRepository, Request $request, $id): Response
    {
        $carrier = $carrierRepository->find($id);
        //IF CATEGORY DOESNT EXIST
        if (!$carrier) {
            $this->addFlash('danger', 'This carrier doesn\'t exist');
            return $this->redirectToRoute('show_carriers');
        }

        $form = $this->createForm(CarrierType::class, $carrier, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($carrier);
            $this->entityManager->flush();
            //message
            $this->addFlash('success', 'Your carrier was updated with success');
            return $this->redirectToRoute('show_carriers');
        }
        return $this->render('admin/carrier/carrier_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE COLOR
    #[Route('/admin/delete/carrier/{id}', name: 'delete_carrier', requirements: ['id' => '\d+'])]
    public function delete(CarrierRepository $carrierRepository, Request $request, $id): Response
    {
        $carrier = $carrierRepository->find($id);
        if (!$carrier) {
            $this->addFlash('danger', 'This carrier doesn\'t exist');
            return $this->redirectToRoute('show_carriers');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken(
            'deleteCarrier' . $id,
            $request->request->get('_token')
        );
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Carrier deleted succesfully');
            $this->entityManager->remove($carrier);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_carriers');
    }
}
