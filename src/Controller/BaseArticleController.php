<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseArticleController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function showHomepage(): Response
    {
        return $this->render('articles/homepage.html.twig');
    }

    /**
     * @Route("/articles/{slug}", name="app_detail")
     * @param string $slug
     * @return Response
     */
    public function showBaseArticles(string $slug): Response
    {
        $comments = [
            'First comment',
            'Second comment',
        ];

        return $this->render('articles/detail.html.twig', [
            'article' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
        ]);
    }
}