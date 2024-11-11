<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\User\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AddressController extends AbstractController
{
    private $entityManager;
    private $security;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security,  CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST USER ADDRESSES
    #[Route('/account/addresses', name: 'account_address')]
    public function index(AddressRepository $addressRepository): Response
    {
        $user = $this->security->getUser();
        $addresses = $addressRepository->findBy(['user' => $user]);
        return $this->render('account/address/index.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    //CREATE OR MODIFY ADDRESS
    #[Route('/account/address/{id}', name: 'account_create_address', defaults: ['id' => null])]
    public function form(Request $request, $id, AddressRepository $addressRepository): Response
    {
        $totalAddresses = $addressRepository->findAll();

        if (count($totalAddresses) > 5) {
            $this->addFlash('danger', 'You have hit the limit of addresses');
            return $this->redirectToRoute('account_address');
        }

        $user = $this->security->getUser();

        //CREATE OR MODIFY IF ID EXIST.
        if ($id) {
            $address = $addressRepository->findOneById($id);

            //security to verify that the user owns this address
            if (!$address or $address->getUser() != $user) {
                $this->addFlash(
                    'danger',
                    'This page doesn\'t exist.'
                );
                return $this->redirectToRoute('account_address');
            }
        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }

        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //save user address
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Your address has been saved.'
            );
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address/addressUser_form.html.twig', [
            'form' => $form,
        ]);
    }

    //DELETE ADDRESS
    #[Route('/account/address/delete/{id}', name: 'account_delete_address', requirements: ['id' => '\d+'])]
    public function delete(AddressRepository  $addressRepository, Request $request, $id): Response
    {
        $address = $addressRepository->find($id);
        if (!$address or $address->getUser() != $this->getUser()) {
            $this->addFlash('danger', 'This carrier doesn\'t exist');
            return $this->redirectToRoute('account_address');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken(
            'deleteAddressUser' . $id,
            $request->request->get('_token')
        );
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Address deleted succesfully');
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('account_address');
    }
}
