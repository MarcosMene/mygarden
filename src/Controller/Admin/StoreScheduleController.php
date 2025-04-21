<?php

namespace App\Controller\Admin;

use App\Entity\StoreSchedule;
use App\Form\Admin\StoreScheduleType;
use App\Repository\StoreScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class StoreScheduleController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // LIST SCHEDULE STORE
    #[Route('/admin/list/schedules', name: 'show_schedules')]
    public function index(StoreScheduleRepository $storeScheduleRepository): Response
    {
        $schedules = $storeScheduleRepository->findAllOrderedByDayOrder();
        return $this->render('admin/schedule/list_storeSchedule.html.twig', [
            'schedules' => $schedules,
        ]);
    }

    // CREATE SCHEDULE
    #[Route('/admin/create/schedule', name: 'create_schedule')]
    public function create(StoreScheduleRepository $storeScheduleRepository, Request $request): Response
    {
        $totalDays = $storeScheduleRepository->findAll();

        if (count($totalDays) == 7) {
            //MESSAGE
            $this->addFlash('danger', 'Your schedule is completed');
            return $this->redirectToRoute('show_schedules');
        }

        $schedule = new StoreSchedule();
        $form = $this->createForm(StoreScheduleType::class, $schedule, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $schedule = $form->getData();

            $this->entityManager->persist($schedule);
            $this->entityManager->flush();
            //MESSAGE
            $this->addFlash('success', 'Your schedule was created with success');
            return $this->redirectToRoute('show_schedules');
        }
        return $this->render('admin/schedule/storeSchedule_form.html.twig', [
            'form' => $form
        ]);
    }

    //DETAIL SCHEDULE
    #[Route('/admin/detail/schedule/{id}', name: 'detail_schedule', requirements: ['id' => '\d+'])]
    public function detail(StoreScheduleRepository $storeScheduleRepository, $id): Response
    {
        $schedule = $storeScheduleRepository->find($id);
        //IF SCHEDULE DOESNT EXIST
        if (!$schedule) {
            $this->addFlash('danger', 'This schedule doesn\'t exist');
            return $this->redirectToRoute('show_schedules');
        }
        return $this->render('admin/schedule/detail_storeSchedule.html.twig', [
            'schedule' => $schedule
        ]);
    }

    //EDIT SCHEDULE
    #[Route('/admin/edit/schedule/{id}', name: 'edit_schedule', requirements: ['id' => '\d+'])]
    public function edit(StoreScheduleRepository $storeScheduleRepository, Request $request, $id): Response
    {
        $schedule = $storeScheduleRepository->find($id);
        //IF SCHEDULE DOESNT EXIST
        if (!$schedule) {
            $this->addFlash('danger', 'This schedule doesn\'t exist');
            return $this->redirectToRoute('show_schedules');
        }

        $form = $this->createForm(StoreScheduleType::class, $schedule, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($schedule);
            $this->entityManager->flush();
            //MESSAGE
            $this->addFlash('success', 'Your schedule was updated with success');
            return $this->redirectToRoute('show_schedules');
        }
        return $this->render('admin/schedule/storeSchedule_form.html.twig', [
            'form' => $form
        ]);
    }

    //DELETE SCHEDULE
    #[Route('/admin/delete/schedule/{id}', name: 'delete_schedule', requirements: ['id' => '\d+'])]
    public function delete(StoreScheduleRepository $storeScheduleRepository, Request $request, $id): Response
    {
        $schedule = $storeScheduleRepository->find($id);
        if (!$schedule) {
            $this->addFlash('danger', 'This schedule doesn\'t exist');
            return $this->redirectToRoute('show_schedules');
        }

        //SECURITY CSRF
        $csrfToken = new CsrfToken('deleteSchedule' . $id, $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->addFlash('danger', 'You don\'t have permission to do that.');
        } else {
            $this->addFlash('success', 'Schedule deleted succesfully');
            $this->entityManager->remove($schedule);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_schedules');
    }
}
