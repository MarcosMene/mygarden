<?php

namespace App\Controller\Admin;

use App\Entity\BlogArticle;
use App\Form\Admin\BlogArticleType;
use App\Repository\BlogArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Handler\UploadHandler;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/blog/articles')]
class BlogController extends AbstractController
{

    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST ARTICLES
    #[Route('/', name: 'show_articles', methods: ['GET'])]
    public function index(
        Request $request,
        BlogArticleRepository $articleRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = trim($request->query->get('q', ''));
        $page = $request->query->getInt('page', 1);

        // SEARCH ARTICLES
        $articlesQuery = $articleRepository->searchArticles($query);

        // PAGE RESULTS
        $articles = $paginator->paginate(
            $articlesQuery, // QUERY
            $page,          // CURRENT PAGE
            5              // LIMIT OF ITEMS PER PAGE
        );

        // IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/blog_article/_table_articles.html.twig', [
                'articles' => $articles,
            ]);
        }

        // IF IT'S NOT AJAX, IT RETURNS THE LIST OF ARTICLES
        return $this->render('admin/blog/list_articles.html.twig', [
            'articles' => $articles,
            'query' => $query,
        ]);
    }

    //CREATE ARTICLE
    #[Route('/create', name: 'create_article')]
    public function new(
        Request $request,
        UploadHandler $uploadHandler
    ): Response {

        $article = new BlogArticle();

        $article->setAuthor($this->getUser());

        $form = $this->createForm(BlogArticleType::class, $article, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //SLUG THE TITLE
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($article->getTitle()));
            $article->setSlug($slug);

            // FIRST, SAVE THE ORIGINAL IMAGE WITH VICHUPLOADERBUNDLE
            $uploadHandler->upload($article, 'articleImageFile');

            // GETS THE NAME OF THE ORIGINAL FILE SAVED BY VICHUPLOADER
            $originalFilename = $article->getArticleImageName();
            $originalPath = $this->getParameter('kernel.project_dir') . '/public/upload/blog/articles/' . $originalFilename;

            if ($originalFilename && file_exists($originalPath)) {
                //NEW NAME FOR THE WEBP IMAGE
                $newFilename = uniqid() . '.webp';
                $destination = $this->getParameter('kernel.project_dir') . '/public/upload/blog/articles/' . $newFilename;

                // CONVERT TO WEBP
                $imagine = new Imagine();
                $image = $imagine->open($originalPath);
                $image->resize(new Box(400, 400))
                    ->save($destination, ['format' => 'webp', 'quality' => 85]);

                // REMOVES THE ORIGINAL FILE AFTER CONVERSION
                unlink($originalPath);

                // UPDATES THE ENTITY WITH THE NEW WEBP FILE NAME
                $article->setArticleImageName($newFilename);
            }


            // SET ARTICLE STATUS
            $status = $request->request->get('status', 'draft');
            if ($status === 'published') {
                $article->setStatus('published');
                $article->setValidated(true);
            } else {
                $article->setStatus('draft');
                $article->setValidated(false);
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_articles');
        }

        return $this->render('admin/blog/article_form.html.twig', [
            'form' => $form->createView(),
            'article' => $article, // PASSING AN EMPTY OBJECT TO AVOID AN ERROR
            'is_edit' => false,
        ]);
    }


    //EDIT ARTICLE
    #[Route('/{id}', name: 'edit_article', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogArticleRepository $blogArticleRepository, $id): Response
    {
        $article = $blogArticleRepository->find($id);


        //IF ARTICLES DOESNT EXIST
        if (!$article) {
            $this->addFlash('danger', 'This article doesn\'t exist.');
            return $this->redirectToRoute('show_articles');
        }

        $form = $this->createForm(BlogArticleType::class, $article, [
            'is_edit' => true, // DEFINE THAT WE ARE EDITING
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagine = new Imagine();

            $articleImageFile = $form->get('articleImageFile')->getData();



            // IF A NEW IMAGE OF THE ARTICLE IS UPLOADED, REPLACE THE OLD ONE
            if ($articleImageFile) {
                $newFilenameArticle = uniqid() . '.webp';
                $destination = $this->getParameter('kernel.project_dir') . '/public/upload/blog/articles/';

                // PROCESSES THE IMAGE AND CONVERTS IT TO WEBP
                $imageArticle = $imagine->open($articleImageFile->getPathname());
                $imageArticle->resize(new Box(400, 400))
                    ->save($destination . $newFilenameArticle, ['format' => 'webp', 'quality' => 85]);

                $article->setArticleImageName($newFilenameArticle);
            }

            if ($article->getAuthor() && $article->getAuthor()->getProfileImageName()) {
                //IF THE AUTHOR HAS AN IMAGE, USE THAT IMAGE
                $article->setAuthorImageName($article->getAuthor()->getProfileImageName());
            } else {
                // IF THE AUTHOR DOESN'T HAVE AN IMAGE, USE THE DEFAULT IMAGE
                $article->setAuthorImageName('default_avatar.jpg');
            }

            // SET ARTICLE STATUS
            $status = $request->request->get('status', 'draft');
            if ($status === 'published') {
                $article->setStatus('published');
                $article->setValidated(true);
            } else {
                $article->setStatus('draft');
                $article->setValidated(false);
            }

            //CREATE SLUG FROM ARTICLE NAME
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug(strtolower($article->getTitle()));
            $article->setSlug($slug);

            $this->entityManager->flush();

            $this->addFlash('success', 'Product updated succesfully');
            return $this->redirectToRoute('show_articles');
        }

        return $this->render('admin/blog/article_form.html.twig', [
            'form' => $form,
            'article' => $article,
            'is_edit' => true
        ]);
    }

    //DETAIL ARTICLE
    #[Route('/{slug}', name: 'detail_article', methods: ['GET'], requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
    public function detail(BlogArticleRepository $blogArticleRepository, $slug): Response
    {
        $article = $blogArticleRepository->findOneBySlug($slug);

        if (!$article) {
            $this->addFlash('danger', 'This article doesn\'t exist.');
            return $this->redirectToRoute('show_articles');
        }

        return $this->render('admin/blog/detail_article.html.twig', [
            'article' => $article,
        ]);
    }



    //DELETE ARTICLE

    #[Route('/{id}', name: 'delete_article', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    // #[IsGranted('ROLE_ADMIN')]
    public function delete(BlogArticleRepository $blogArticleRepository, Request $request, $id, Filesystem $filesystem): Response
    {
        $blogArticle = $blogArticleRepository->find($id);

        if (!$blogArticle) {
            $this->addFlash('danger', 'This tag doesn\'t exist.');
            return $this->redirectToRoute('show_articles');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteBlogArticle' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
            return $this->redirectToRoute('show_articles');
        }

        // PATH OF IMAGES
        $articleImagePath = $this->getParameter('blog_images') . '/' . $blogArticle->getArticleImageName();
        $authorImagePath = $this->getParameter('user_images') . '/' . $blogArticle->getAuthor()?->getProfileImageFile();


        // REMOVE ARTICLE IMAGE
        if ($blogArticle->getArticleImageName() && $filesystem->exists($articleImagePath)) {
            $filesystem->remove($articleImagePath);
        }

        //ONLY REMOVE THE AUTHOR'S IMAGE IF THEY NO LONGER HAVE ARTICLES
        if ($blogArticle->getAuthor() && $blogArticle->getAuthor()->getProfileImageName()) {
            $remainingArticles = $this->entityManager->getRepository(BlogArticle::class)->findBy(['author' => $blogArticle->getAuthor()]);
            if (count($remainingArticles) === 1) { // THE CURRENT ARTICLE IS THE ONLY ONE  RESTANTE
                if ($filesystem->exists($authorImagePath)) {
                    $filesystem->remove($authorImagePath);
                }
            }
        }

        $this->entityManager->remove($blogArticle);
        $this->entityManager->flush();

        $this->addFlash('success', 'Article deleted succesfully');
        return $this->redirectToRoute('show_articles');
    }
}
