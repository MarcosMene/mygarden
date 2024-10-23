<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(): Response
    {
        return $this->render('pages/blog.html.twig');
    }
    #[Route('/blog/{slug}', name: 'blog_article', requirements: ['slug' => '[a-zA-Z0-9-_]+'])]
    public function list(): Response
    {
        return $this->render('blog/show.html.twig');
    }
}
