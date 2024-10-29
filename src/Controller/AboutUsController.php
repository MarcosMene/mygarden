<?php

namespace App\Controller;

use App\Repository\EmployeesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutUsController extends AbstractController
{
    #[Route('/about-us', name: 'about_us')]
    public function index(EmployeesRepository $employeesRepository): Response
    {

        //FIND EMPLOYEES
        $employees = $employeesRepository->findAll();

        return $this->render('pages/about.html.twig', [
            'employees' => $employees
        ]);
    }
}
