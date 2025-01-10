<?php

namespace App\Controller\Account;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/change-password', name: 'account_modify_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, UserPasswordHasherInterface $encoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('current_password')->getData();

            if ($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_pwd);

                //set new password
                $user->setPassword($password);

                //upgrade password
                $this->entityManager->flush();
                $this->addFlash('success', 'Your  password has been changed successfully.');
                return $this->redirectToRoute('account_modify_password');
            } else {
                $this->addFlash('danger', 'Your  old password is incorrect. Please try again.');
            }
        }

        return $this->render('account/password/index.html.twig', [
            'form' => $form
        ]);
    }
}
