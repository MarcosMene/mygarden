<?php

namespace App\Controller;

use App\Entity\BlogArticle;
use App\Entity\BlogComment;
use App\Form\Users\BlogCommentType;
use App\Repository\BlogArticleRepository;
use App\Repository\BlogCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class BlogController extends AbstractController
{

    //LIST ARTICLES
    #[Route('/blog', name: 'blog', methods: ['GET'])]
    public function index(BlogArticleRepository $blogArticleRepository, Request $request)
    {
        $category = $request->query->get('category');
        $year = $request->query->get('year');
        $name = $request->query->get('name');

        // TAKING THE REQUEST PAGE, DEFAULT IS 1
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit; // CALCULATES DISPLACEMENT CORRECTLY

        // SEARCH ARTICLES USING THE CORRECT FILTERS AND PAGINATION
        $articles = $blogArticleRepository->findByFilters($category, $year, $name, $offset, $limit);

        // SEARCH CATEGORIES AND YEARS FOR FILTERS
        $categories = $blogArticleRepository->findCategories();
        $years = $blogArticleRepository->findYears();

        return $this->render('pages/blog.html.twig', [
            'categories' => $categories,
            'years' => $years,
            'articles' => $articles,
        ]);
    }

    //LOAD MORE ARTICLES FOR BUTTON LOAD MORE
    #[Route('/blog/load-more', name: 'blog_load_more', methods: ['GET'])]
    public function loadMore(Request $request, BlogArticleRepository $articleRepository): JsonResponse
    {
        $offset = (int) $request->query->get('offset', 0);
        $search = $request->query->get('name', '');
        $category = $request->query->get('category', '');
        $year = $request->query->get('year', '');

        $articles = $articleRepository->findByFilters($search, $category, $year, $offset);

        // COUNT TOTAL ARTICLES AVAILABLE FOR THE FILTERS APPLIED
        $totalArticles = count($articleRepository->findByFilters($search, $category, $year, 0, true));

        $html = $this->renderView('_partials/blog/_articles.html.twig', ['articles' => $articles]);

        return $this->json([
            'html' => $html,
            'totalArticles' => $totalArticles,
            'no_more' => count($articles) < 10
        ]);
    }

    //SHOW ARTICLE BY SLUG
    #[Route('/blog/{slug}', name: 'blog_show', methods: ['GET', 'POST'], requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
    public function show(
        BlogArticleRepository $blogArticleRepository,
        BlogCommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        $slug
    ): Response {

        // SEARCH FOR THE ARTICLE BY SLUG
        $article = $blogArticleRepository->findOneBySlug($slug);


        if (!$article) {
            throw $this->createNotFoundException('Artigo não encontrado');
        }

        // SEARCH ARTICLE COMMENTS
        $comments = $commentRepository->findApprovedCommentsByPost($article);

        // CHECK IF THE USER IS LOGGED IN
        $user = $security->getUser();
        $hasCommented = false;

        if ($user) {
            // CHECK IF THE USER HAS ALREADY COMMENTED ON THIS ARTICLE
            $existingComment = $commentRepository->findOneBy(['article' => $article, 'user' => $user]);
            $hasCommented = $existingComment !== null;

            // CREATE A COMMENT FORM IF YOU HAVEN'T COMMENTED YET
            if (!$hasCommented) {
                $comment = new BlogComment();
                $form = $this->createForm(BlogCommentType::class, $comment);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $comment->setArticle($article);
                    $comment->setUser($user);
                    $comment->setCreatedAt(new \DateTimeImmutable());

                    $entityManager->persist($comment);
                    $entityManager->flush();
                    $this->addFlash('success', 'Your comment is being uploaded. Thank you very much.');

                    return $this->redirectToRoute('blog_show', ['slug' => $slug]);
                }
            }
        }

        return $this->render('blog/show.html.twig', [
            "article" => $article,
            'author' => $article->getAuthor(),

            //IF AUTHOR IMAGE DOESNT EXIST USE DEFAULT AVATAR
            'authorImage' => $article->getAuthor() ? $article->getAuthor()->getProfileImageName() : 'default_avatar.webp',
            'comments' => $comments,
            'form' => isset($form) ? $form->createView() : null,
            'hasCommented' => $hasCommented,
            'user' => $user,
            'existingComment' => $existingComment ?? null, //ENSURES THAT THE VARIABLE ALWAYS EXISTS
        ]);
    }
}
