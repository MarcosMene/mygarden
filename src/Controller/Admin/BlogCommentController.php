<?php

namespace App\Controller\Admin;

use App\Repository\BlogCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BlogCommentController extends AbstractController
{
    #[Route('/admin/comments/{id}/{action}', methods: ['POST'], name: 'admin_comment_action')]
    #[IsGranted('ROLE_ADMIN')]
    public function changeApprovalStatus($id, $action, EntityManagerInterface $entityManager, BlogCommentRepository $commentRepository): JsonResponse
    {
        $comment = $commentRepository->find($id);

        if (!$comment) {
            return new JsonResponse(['success' => false, 'message' => 'Comentário não encontrado.']);
        }

        if ($action === 'approve') {
            $comment->setApproved(true);
        } elseif ($action === 'reject') {
            $comment->setApproved(false);
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Ação inválida.']);
        }

        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
