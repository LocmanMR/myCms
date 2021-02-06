<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    /**
     * @Route("/admin/tags", name="app_admin_tags")
     * @param Request $request
     * @param TagRepository $tagRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $tagsQuery = $tagRepository->findAllWithSearchQuery(
            $request->query->get('q'),
            $request->query->has('showDeleted'),
        );

        $pagination = $paginator->paginate(
            $tagsQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/tags/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}