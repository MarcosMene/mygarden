<?php

namespace App\Controller\Admin;

use App\Entity\BlogTag;
use App\Form\Admin\BlogTagType;
use App\Repository\BlogTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/blog/article')]
class BlogTagController extends AbstractController
{

    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }


    //LIST TAGS 
    #[Route('/tags', name: 'show_blogTags', methods: ['GET'])]
    public function index(BlogTagRepository $blogTagRepository): Response
    {
        $blogTags = $blogTagRepository->findAllOrderedByAppearTag();

        return $this->render('admin/blog_tag/list_tags.html.twig', [
            'blogTags' => $blogTags
        ]);
    }


    //CREATE BLOG TAG
    #[Route('/tag/create', name: 'create_blogTag')]
    public function new(Request $request, BlogTagRepository $blogTagRepository): Response
    {
        $blogTag = new BlogTag();
        $form = $this->createForm(BlogTagType::class, $blogTag, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //CHECK IF TAG ALREADY EXISTS
            $existingBlogTag = $blogTagRepository->findOneBy(['name' => $blogTag->getName()]);
            if ($existingBlogTag) {
                $form->get('name')->addError(new FormError('This tag is already taken.'));
            } else {
                $blogTag = $form->getData();

                $this->entityManager->persist($blogTag);
                $this->entityManager->flush();

                //MESSAGE
                $this->addFlash('success', 'Your tag was created with success');
                return $this->redirectToRoute('show_blogTags');
            }
        }
        return $this->render('admin/blog_tag/tag_form.html.twig', [
            'form' => $form
        ]);
    }

    //EDIT TAG
    #[Route('/tag/{id}', name: 'edit_blogTag', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogTagRepository $blogTagRepository, $id): Response
    {
        $tag = $blogTagRepository->find($id);


        //IF TAG DOESNT EXIST
        if (!$tag) {
            $this->addFlash('danger', 'This tag doesn\'t exist.');
            return $this->redirectToRoute('show_blogTags');
        }

        $form = $this->createForm(BlogTagType::class, $tag, ['is_edit' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $tag = $form->getData();

            $this->entityManager->flush();

            $this->addFlash('success', 'Tag updated succesfully');
            return $this->redirectToRoute('show_blogTags');
        }
        return $this->render('admin/blog_tag/tag_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE BLOG TAG
    #[Route('/tag/{id}', name: 'delete_blogTag', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(BlogTagRepository $blogTagRepository, Request $request, $id): Response
    {
        $blogTag = $blogTagRepository->find($id);

        if (!$blogTag) {
            $this->addFlash('danger', 'This tag doesn\'t exist.');
            return $this->redirectToRoute('show_blogTags');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteBlogTag' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('success', 'Tag deleted succesfully');
            $this->entityManager->remove($blogTag);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_blogTags');
    }
}
