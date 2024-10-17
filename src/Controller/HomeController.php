<?php

namespace App\Controller;

use App\Entity\StoreSchedule;
use App\Repository\StoreScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(StoreScheduleRepository $storeScheduleRepository): Response
    {

        $shopSchedules = $storeScheduleRepository->findBy(array(), array('dayOrder' => 'ASC'));

        return $this->render('pages/home.html.twig', [
            'shopSchedules' => $shopSchedules
        ]);
    }
}