<?php

namespace App\Controller\Admin;

use App\Entity\Employees;
use App\Form\EmployeeType;
use App\Repository\EmployeesRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class EmployeesController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    //LIST EMPLOYEES
    #[Route('/admin/list/employees', name: 'show_employees')]
    public function list(EmployeesRepository $employeesRepository): Response
    {
        $employees = $employeesRepository->findAll();
        return $this->render('admin/employee/list_employees.html.twig', [
            'employees' => $employees,
        ]);
    }

    //CREATE EMPLOYEE
    #[Route('/admin/create/employee', name: 'create_employee')]
    public function create(PostsRepository $postsRepository, Request $request): Response
    {
        $postCounts = $postsRepository->findAll();

        if (empty($postCounts)) {
            $this->addFlash('danger', 'You must create at least one post before creating an employee');
            return $this->redirectToRoute('create_post');
        } else {
            $employee = new Employees();
            $form = $this->createForm(EmployeeType::class, $employee, ['is_edit' => false]); // is_edit is used to determine whether the form is for creating or editing an employee

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $employee->setJoinDate(new \DateTime('now'));

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
    #[Route('/admin/detail/employee/{id}', name: 'detail_employee', requirements: ['id' => '\d+'])]
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
    #[Route('/admin/edit/employee/{id}', name: 'edit_employee', requirements: ['id' => '\d+'])]
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
            $this->entityManager->persist($employee);
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

        //security csrf
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
