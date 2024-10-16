<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/admin/show/categories', name: 'show_categories')]
    public function index(CategoryRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();

        return $this->render('admin/category/list_category.html.twig', [
            'categories' => $categories
        ]);
    }

    // CREATE CATEGORY 
    #[Route('/admin/create/category', name: 'create_category')]
    public function create(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, ['is_edit' => false]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            // CREATE SLUG FROM CATEGORY NAME 
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($category->getName()));
            $category->setSlug($slug);
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            //message
            $this->addFlash('success', 'Your category was created with success');
            return $this->redirectToRoute('show_categories');
        }
        return $this->render('admin/category/category_form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/detail/category/{slug}', name: 'detail_category', requirements: ['slug' => '[a-z0-9-\-]+'])]
    public function detail(CategoryRepository $categoriesRepository, $slug): Response
    {
        $category = $categoriesRepository->findOneBySlug($slug);

        //IF CATEGORY DOESNT EXIST
        if (!$category) {
            $this->addFlash('danger', 'This category doesn\'t exist');
            return $this->redirectToRoute('show_categories');
        }
        return $this->render('admin/category/detail_category.html.twig', [
            'category' => $category
        ]);
    }

    //EDIT CATEGORY
    #[Route('/admin/edit/category/{id}', name: 'edit_category', requirements: ['id' => '\d+'])]
    public function edit(Request $request, CategoryRepository $categoriesRepository, $id)
    {
        $category = $categoriesRepository->find($id);

        //IF CATEGORY DOESNT EXIST
        if (!$category) {
            $this->addFlash('danger', 'This category doesn\'t exist');
            return $this->redirectToRoute('show_categories');
        }

        $form = $this->createForm(CategoryType::class, $category, ['is_edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            //CREATE SLUG FROM CATEGORY NAME
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($category->getName()));
            $category->setSlug($slug);
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category updated succesfully');
            return $this->redirectToRoute('show_categories');
        }
        return $this->render('admin/category/category_form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/delete/category/{id}', name: 'delete_category', requirements: ['id' => '\d+'])]
    public function delete(CategoryRepository $categoriesRepository, Request $request, $id): Response
    {
        $category = $categoriesRepository->find($id);
        if (!$category) {
            $this->addFlash('danger', 'This category doesn\'t exist');
            return $this->redirectToRoute('show_categories');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken(
            'deleteCategory' . $id,
            $request->request->get('_token')
        );
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have accesss to it');
        } else {
            $this->addFlash('success', 'Category deleted succesfully');
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_categories');
    }
}
