<?php

namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @Route("/articles/{slug}/vote/up", methods={"POST"}, name="app_article_up")
     * @param Article $article
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function voteUp(Article $article, EntityManagerInterface $em): JsonResponse
    {
        $article->setVote();
        $em->flush();

        return $this->json(['voteCount' => $article->getVoteCount()]);
    }

    /**
     * @Route("/articles/{slug}/vote/down", methods={"POST"}, name="app_article_down")
     * @param Article $article
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function voteDown(Article $article, EntityManagerInterface $em): JsonResponse
    {
        $article->pickUpVote();
        $em->flush();

        return $this->json(['voteCount' => $article->getVoteCount()]);
    }
}