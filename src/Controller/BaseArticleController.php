<?php
declare(strict_types=1);


namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function showHomepage(ArticleRepository $articleRepository): Response
    {
        return $this->render('articles/homepage.html.twig', [
            'articles' => $articleRepository->findLatestPublished(),
        ]);
    }

    /**
     * @Route("/articles/{slug}", name="app_detail")
     * @param Article $article
     * @return Response
     */
    public function showBaseArticles(Article $article): Response
    {
        /** @var array $comments Temporary variable */
        $comments = [
            'First comment',
            'Second comment',
        ];

        return $this->render('articles/detail.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }
}