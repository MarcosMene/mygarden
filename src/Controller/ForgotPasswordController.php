<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/forgot-password', name: 'forgot_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData();

            $user = $userRepository->findOneByEmail($email);

            //send a message to user
            $this->addFlash('success', 'If your email address exists, you will receive an email to reset your password.');

            //IF USER EXIST
            if ($user) {

                //create token on database
                $token = bin2hex(random_bytes(15));
                $user->setToken($token);

                $date = new DateTime();
                $date->modify('+10 minutes');

                $user->setTokenExpireAt($date);

                $this->em->flush();

                //send email to user to confirm new account
                $mail = new Mail();
                $vars = [
                    'link' => $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                ];
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Reset your password', 'forgotpassword.html', $vars);
            }
        }

        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/password/reset/{token}', name: 'reset_password')]
    public function reset(Request $request, UserRepository $userRepository, $token): Response
    {
        if (!$token) {
            return $this->redirectToRoute('forgot_password');
        }
        $user = $userRepository->findOneByToken($token);
        $now = new DateTime();

        if (!$user || $now > $user->getTokenExpireAt()) {
            //send a message to user
            $this->addFlash('danger', 'Your token has expired, please try again.');
            return $this->redirectToRoute('forgot_password'); // redirect to forgot password page
        }

        $form = $this->createForm(ResetPasswordFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //reset token and ExpireAt for forgot password
            $user->setToken(null);
            $user->setTokenExpireAt(null);

            $this->em->flush();
            $this->addFlash('success', 'Your password has been changed.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('password/reset.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
