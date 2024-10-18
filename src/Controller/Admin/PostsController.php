<?php

namespace App\Controller\Admin;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class PostsController extends AbstractController
{

    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST POSTS
    #[Route('/admin/show/posts', name: 'show_posts')]
    public function index(PostsRepository $postsRepository): Response
    {
        $posts = $postsRepository->findAll();
        return $this->render('admin/post_employee/list_posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    //CREATE POST
    #[Route('/admin/create/post', name: 'create_post')]
    public function create(Request $request): Response
    {
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            //message
            $this->addFlash('success', 'Your post was created with success');
            return $this->redirectToRoute('show_posts');
        }
        return $this->render('admin/post_employee/post_form.html.twig', [
            'form' => $form,
        ]);
    }

    //DETAIL POST
    #[Route('/admin/detail/post/{id}', name: 'detail_post', requirements: ['id' => '\d+'])]
    public function detail(PostsRepository $postsRepository, $id): Response
    {
        $post = $postsRepository->find(['id' => $id]);

        if (!$post) {
            $this->addFlash('danger', 'Post not found');
            return $this->redirectToRoute('show_posts');
        }

        return $this->render('admin/post_employee/detail_post.html.twig', [
            'post_detail' => $post
        ]);
    }

    //EDIT POST
    #[Route('/admin/edit/post/{id}', name: 'edit_post', requirements: ['id' => '\d+'])]
    public function edit(PostsRepository $postsRepository, $id, Request $request): Response
    {
        $post = $postsRepository->find(['id' => $id]);

        //IF POST DOESNT EXIST
        if (!$post) {
            $this->addFlash('danger', 'This post doesn\'t exist');
            return $this->redirectToRoute('show_posts');
        }

        $form = $this->createForm(PostsType::class, $post, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'The post was updated succesfully');
            return $this->redirectToRoute('show_posts');
        }
        return $this->render('admin/post_employee/post_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE POST
    #[Route('/admin/delete/post/{id}', name: 'delete_post', requirements: ['id' => '\d+'])]
    public function delete(postsRepository $postsRepository, Request $request, $id): Response
    {
        $post = $postsRepository->find($id);
        if (!$post) {
            $this->addFlash('danger', 'This post doesn\'t exist');
            return $this->redirectToRoute('show_posts');
        }

        //security csrf
        $csrfToken = new CsrfToken('deletePost' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that..');
        } else {
            $this->addFlash('success', 'Post deleted succesfully');
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_posts');
    }
}
