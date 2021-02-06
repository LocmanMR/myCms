<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="app_admin_comments")
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $commentsQuery = $commentRepository->findAllWithSearchQuery(
            $request->query->get('q'),
            $request->query->has('showDeleted'),
        );

        $pagination = $paginator->paginate(
            $commentsQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/comments/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
