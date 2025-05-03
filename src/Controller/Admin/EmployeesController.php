<?php

namespace App\Controller\Admin;

use App\Entity\Employees;
use App\Form\Admin\EmployeeType;
use App\Repository\EmployeesRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Handler\UploadHandler;
use Knp\Component\Pager\PaginatorInterface;

class EmployeesController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST EMPLOYEES
    #[Route('/admin/list/employees', name: 'show_employees', methods: ['GET'])]
    public function list(EmployeesRepository $employeesRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $query = trim($request->query->get('query', ''));
        $page = $request->query->getInt('page', 1);

        // SEARCH PRODCUTS
        $employeesQuery = $employeesRepository->searchEmployees($query);

        // PAGE RESULTS
        $employees = $paginator->paginate(
            $employeesQuery, // QUERY
            $page,          // CURRENT PAGE
            5              // LIMIT OF ITEMS PER PAGE
        );

        // IF THE REQUEST IS AJAX, IT ONLY RETURNS THE TABLE OF ARTICLES
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/_partials/employees/_table_employees.html.twig', [
                'employees' => $employees,
            ]);
        }

        // IF IT'S NOT AJAX, IT RETURNS THE LIST OF ARTICLES
        return $this->render('admin/employee/list_employees.html.twig', [
            'employees' => $employees,
            'query' => $query,
        ]);
    }

    //CREATE EMPLOYEE
    #[Route('/admin/create/employee', name: 'create_employee')]
    public function create(PostsRepository $postsRepository, UploadHandler $uploadHandler, Request $request): Response
    {
        //VERIFY IF EMPLOYEES EXIST ON THE DATABASE
        $postCounts = $postsRepository->findAll();

        if (empty($postCounts)) {
            $this->addFlash('danger', 'You must create at least one post before creating an employee');
            return $this->redirectToRoute('create_post');
        } else {
            $employee = new Employees();
            $form = $this->createForm(EmployeeType::class, $employee, ['is_edit' => false]); // IS_EDIT IS USED TO DETERMINE WHETHER THE FORM IS FOR CREATING OR EDITING AN EMPLOYEE

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $employee->setJoinDate(new \DateTime('now'));

                //GET INFO FROM FORM
                $employee = $form->getData();

                //CHECK THAT THE FILE HAS BEEN SENT
                if ($employee->getImageFile()) {
                    // SAVE THE IMAGE WITH VICHUPLOADERBUNDLE
                    $uploadHandler->upload($employee, 'imageFile');
                }

                //  GETS THE NAME OF THE ORIGINAL FILE SAVED BY VICHUPLOADER
                $originalFilename = $employee->getImageName();
                $originalPath = $this->getParameter('kernel.project_dir') . '/public/upload/employees/' . $originalFilename;

                if ($originalFilename && file_exists($originalPath)) {
                    // NEW NAME FOR THE WEBP IMAGE
                    $newFilename = uniqid() . '.webp';
                    $destination = $this->getParameter('kernel.project_dir') . '/public/upload/employees/' . $newFilename;

                    // CONVERT TO WEBP
                    $imagine = new Imagine();
                    $image = $imagine->open($originalPath);
                    $image->resize(new Box(350, 350))
                        ->save($destination, ['format' => 'webp', 'quality' => 85]);

                    //REMOVES THE ORIGINAL FILE AFTER CONVERSION
                    unlink($originalPath);

                    // UPDATES THE ENTITY WITH THE NEW WEBP FILE NAME
                    $employee->setImageName($newFilename);
                }

                $this->entityManager->persist($employee);
                $this->entityManager->flush();
                $this->addFlash('success', 'Employee was created with success.');
                return $this->redirectToRoute('show_employees');
            }
        }
        return $this->render('admin/employee/employee_form.html.twig', [
            'form' => $form,
        ]);
    }

    //DETAIL EMPLOYEE
    #[Route('/admin/detail/employee/{id}', name: 'detail_employee', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function detail(EmployeesRepository $employeesRepository, $id): Response
    {
        $employee = $employeesRepository->find(['id' => $id]);

        if (!$employee) {
            $this->addFlash('danger', 'Employee not found');
            return $this->redirectToRoute('show_employees');
        }
        return $this->render('admin/employee/detail_employee.html.twig', [
            'employee_detail' => $employee
        ]);
    }

    //EDIT EMPLOYEE
    #[Route('/admin/edit/employee/{id}', name: 'edit_employee', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(EmployeesRepository $employeesRepository, $id, Request $request): Response
    {
        $employee = $employeesRepository->findOneBy(['id' => $id]);

        //IF EMPLOYEE DOESNT EXIST
        if (!$employee) {
            $this->addFlash('danger', 'This employee doesn\'t exist');
            return $this->redirectToRoute('show_employees');
        }

        $form = $this->createForm(EmployeeType::class, $employee, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Employee updated succesfully');
            return $this->redirectToRoute('show_employees');
        }
        return $this->render('admin/employee/employee_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE EMPLOYEE
    #[Route('/admin/delete/employee/{id}', name: 'delete_employee', requirements: ['id' => '\d+'])]
    public function delete(EmployeesRepository $employeesRepository, Request $request, $id): Response
    {
        $employee = $employeesRepository->find(['id' => $id]);
        if (!$employee) {
            $this->addFlash('danger', 'This employee doesn\'t exist');
            return $this->redirectToRoute('show_employees');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteEmployee' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Employee deleted succesfully');
            $this->entityManager->remove($employee);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_employees');
    }
}
