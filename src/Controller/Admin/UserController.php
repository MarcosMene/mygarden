<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Knp\Component\Pager\PaginatorInterface;
use Vich\UploaderBundle\Handler\UploadHandler;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //LIST USERS
    #[Route('/admin/list/users', name: 'show_users', methods: ['GET'])]
    public function list(UserRepository $userRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $query = trim($request->query->get('query', ''));
        $page = $request->query->getInt('page', 1);

        // SEARCH USERS
        $usersQuery = $userRepository->searchUsers($query);

        // PAGE RESULTS
        $users = $paginator->paginate(
            $usersQuery, // QUERY
            $page,          // CURRENT PAGE
            5              // LIMIT OF ITEMS PER PAGE
        );

        // IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/users/_table_users.html.twig', [
                'users' => $users,
            ]);
        }

        // IF IT'S NOT AJAX, IT RETURNS THE LIST OF ARTICLES
        return $this->render('admin/user/list_user.html.twig', [
            'users' => $users,
            'query' => $query,
        ]);
    }

    //DETAIL USER
    #[Route('/admin/detail/user/{id}', name: 'detail_user',  requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneById($id);

        if (!$user) {
            $this->addFlash('danger', 'This user doesnt\'t exist.');
            return $this->redirectToRoute('show_users');
        }
        return $this->render('admin/user/detail_user.html.twig', [
            'user_detail' => $user
        ]);
    }

    //EDIT USER
    #[Route('/admin/edit/user/{id}', name: 'edit_user',  requirements: ['id' => '\d+'])]
    public function edit(Request $request, $id, UserRepository $userRepository, UploadHandler $uploadHandler): Response
    {
        $user = $userRepository->findOneById($id);

        // IF USER DOESNT EXIST
        if (!$user) {
            $this->addFlash('danger', 'This user doesn\'t exist.');
            return $this->redirectToRoute('show_users');
        }

        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //CHECK THAT THE FILE HAS BEEN SENT
            if ($user->getProfileImageFile()) {
                // SAVE THE IMAGE WITH VICHUPLOADERBUNDLE
                $uploadHandler->upload($user, 'profileImageFile');
            }

            //  GETS THE NAME OF THE ORIGINAL FILE SAVED BY VICHUPLOADER
            $originalFilename = $user->getProfileImageName();
            $originalPath = $this->getParameter('kernel.project_dir') . '/public/upload/users/' . $originalFilename;

            if ($originalFilename && file_exists($originalPath)) {
                // NEW NAME FOR THE WEBP IMAGE
                $newFilename = uniqid() . '.webp';
                $destination = $this->getParameter('kernel.project_dir') . '/public/upload/users/' . $newFilename;

                // CONVERT TO WEBP
                $imagine = new Imagine();
                $image = $imagine->open($originalPath);
                $image->resize(new Box(150, 150))
                    ->save($destination, ['format' => 'webp', 'quality' => 85]);

                //REMOVES THE ORIGINAL FILE AFTER CONVERSION
                unlink($originalPath);

                // UPDATES THE ENTITY WITH THE NEW WEBP FILE NAME
                $user->setProfileImageName($newFilename);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('show_users');
        }

        return $this->render('admin/user/user_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //TOGGLE ACTIVE DEACTIVE USER
    #[Route('/user/toggle/{id}', name: 'user_toggle', methods: ['POST'])]
    public function toggleUserStatus(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['success' => false, 'message' => 'Access denied'], 403);
        }

        try {
            $user->setActive(!$user->isActive());
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'isActive' => $user->isActive()]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
}
