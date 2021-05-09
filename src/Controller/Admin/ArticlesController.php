<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticlesController
 * @package App\Controller\Admin
 * @method User|null getUser()
 */
class ArticlesController extends AbstractController
{
    /**
     * @isGranted("ROLE_ADMIN_ARTICLE")
     * @Route("admin/articles", name="app_admin_articles")
     */
    public function index(
        ArticleRepository $articleRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $articlesQuery = $articleRepository->searchArticles(
            $request->query->get('q'),
            $request->query->has('showDeleted'),
        );

        $pagination = $paginator->paginate(
            $articlesQuery,
            $request->query->getInt('page', 1),
            (int)$request->query->get('count') ?: 20
        );

        return $this->render('admin/article/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            $this->addFlash('flash_message', 'Article created');

            return $this->redirectToRoute('app_admin_articles');
        }

        return $this->render('admin/article/create.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/articles/{id}/edit", name="app_admin_articles_edit")
     * @IsGranted("MANAGE", subject="article")
     * @param Article $article
     * @return Response
     */
    public function edit(Article $article): Response
    {
        return new Response('Edit article' . $article->getTitle());
    }
}
