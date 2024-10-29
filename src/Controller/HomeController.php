<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use App\Repository\StoreScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(StoreScheduleRepository $storeScheduleRepository, ProductRepository $productRepository, GalleryRepository $galleryRepository, HeaderRepository $headerRepository): Response
    {
        //FIND HEADERS FOR CAROUSEL
        $headers = $headerRepository->findAllOrderedByAppear();

        //FIND OUR PRODUCTS
        $products = $productRepository->findAllWithLimit();

        //FIND GALLERY IMAGES
        $galleries = $galleryRepository->findAll();

        //STORE SCHEDULES
        $shopSchedules = $storeScheduleRepository->findBy(array(), array('dayOrder' => 'ASC'));

        return $this->render('pages/home.html.twig', [
            'shopSchedules' => $shopSchedules,
            'products' => $products,
            'galleries' => $galleries,
            'headers' => $headers
        ]);
    }

    #[Route('/filter-products', name: "filter_products", methods: ['GET'])]
    public function filterProducts(Request $request, ProductRepository $productRepository): JsonResponse
    {
        // Check if the request is an AJAX request
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException('Access denied.');
        }

        $filterType = $request->query->get('filter', 'all');

        switch ($filterType) {
            case 'best-seller':
                $products = $productRepository->findBestSellers();
                break;
            case 'promotion':
                $products = $productRepository->findPromotions();
                break;
            case 'new':
                $products = $productRepository->findNewArrivals();
                break;
                // case 'top-rated':
                //     $products = $productRepository->findTopRated();
                //     break;
            default:
                $products = $productRepository->findAllWithLimit(12);
                break;
        }

        return new JsonResponse([
            'html' => $this->renderView('_partials/homepage/_product_gallery.html.twig', [
                'products' => $products
            ])
        ]);
    }
}
