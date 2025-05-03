<?php

namespace App\Controller\Admin;

use App\Entity\BlogCategory;
use App\Form\Admin\BlogCategoryType;
use App\Repository\BlogCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/blog/article')]
class BlogCategoryController extends AbstractController
{

    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }


    //LIST CATEGORIES 
    #[Route('/categories', name: 'show_blogCategories', methods: ['GET'])]
    public function index(BlogCategoryRepository $blogCategoryRepository): Response
    {
        $blogCategories = $blogCategoryRepository->findAllOrderedByAppearCategory();

        return $this->render('admin/blog_category/list_categories.html.twig', [
            'blogCategories' => $blogCategories
        ]);
    }

    #[Route('/category/create', name: 'create_blogCategory')]
    public function new(Request $request, BlogCategoryRepository $blogCategoryRepository): Response
    {
        $blogCategory = new BlogCategory();
        $form = $this->createForm(BlogCategoryType::class, $blogCategory, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //CHECK IF CATEGORY ALREADY EXISTS
            $existingBlogCategory = $blogCategoryRepository->findOneBy(['name' => $blogCategory->getName()]);
            if ($existingBlogCategory) {
                $form->get('name')->addError(new FormError('This category is already taken.'));
            } else {
                $blogCategory = $form->getData();

                $this->entityManager->persist($blogCategory);
                $this->entityManager->flush();

                //MESSAGE
                $this->addFlash('success', 'Your category was created with success');
                return $this->redirectToRoute('show_blogCategories');
            }
        }
        return $this->render('admin/blog_category/category_form.html.twig', [
            'form' => $form
        ]);
    }

    //EDIT CATEGORY
    #[Route('/category/{id}', name: 'edit_blogCategory', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogCategoryRepository $blogCategoryRepository, $id): Response
    {
        $category = $blogCategoryRepository->find($id);

        //IF CATEGORY DOESNT EXIST
        if (!$category) {
            $this->addFlash('danger', 'This category doesn\'t exist.');
            return $this->redirectToRoute('show_blogCategories');
        }

        $form = $this->createForm(BlogCategoryType::class, $category, ['is_edit' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $this->entityManager->flush();

            $this->addFlash('success', 'Category updated succesfully');
            return $this->redirectToRoute('show_blogCategories');
        }
        return $this->render('admin/blog_category/category_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE BLOG CATEGORY
    #[Route('/category/{id}', name: 'delete_blogCategory', requirements: ['id' => '\d+'])]
    public function delete(BlogCategoryRepository $blogCategoryRepository, Request $request, $id): Response
    {
        $blogCategory = $blogCategoryRepository->find($id);

        if (!$blogCategory) {
            $this->addFlash('danger', 'This category doesn\'t exist.');
            return $this->redirectToRoute('show_blogCategories');
        }


        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteBlogCategory' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Category deleted succesfully');
            $this->entityManager->remove($blogCategory);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_blogCategories');
    }
}
