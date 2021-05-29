<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
    protected const DEFAULT_ARTICLES_SHOW_CONT = 20;

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
            $request->query->getInt('count') ?: self::DEFAULT_ARTICLES_SHOW_CONT
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

        if ($this->handleFormRequest($form, $em, $request)) {

            $this->addFlash('flash_message', 'Article created');

            return $this->redirectToRoute('app_admin_articles');
        }

        return $this->render('admin/article/create.html.twig', [
            'articleForm' => $form->createView(),
            'showError' => $form->isSubmitted(),
        ]);
    }

    /**
     * @Route("/admin/articles/{id}/edit", name="app_admin_articles_edit")
     * @IsGranted("MANAGE", subject="article")
     * @param Article $article
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function edit(Article $article, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);

        if ($article = $this->handleFormRequest($form, $em, $request)) {

            $this->addFlash('flash_message', 'Article changed');

            return $this->redirectToRoute('app_admin_articles_edit', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('admin/article/edit.html.twig', [
            'articleForm' => $form->createView(),
            'showError' => $form->isSubmitted(),
        ]);
    }

    private function handleFormRequest(FormInterface $form, EntityManagerInterface $em, Request $request): ?Article
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            return $article;
        }

        return null;
    }
}
