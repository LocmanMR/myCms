<?php
declare(strict_types=1);

namespace App\Controller;


use App\Service\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseArticleController extends AbstractController
{

    private $articleProvider;

    public function __construct(ArticleProvider $articleProvider)
    {
        $this->articleProvider = $articleProvider;
    }

    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function showHomepage(): Response
    {
        return $this->render('articles/homepage.html.twig', [
            'articles' => $this->articleProvider->articles(),
        ]);
    }

    /**
     * @Route("/articles/{slug}", name="app_detail")
     * @param string $slug
     * @return Response
     */
    public function showBaseArticles(string $slug): Response
    {
        /** @var array $comments Temporary variable */
        $comments = [
            'First comment',
            'Second comment',
        ];

        return $this->render('articles/detail.html.twig', [
            'article' => $this->articleProvider->article(),
            'comments' => $comments,
        ]);
    }
}