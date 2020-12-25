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
     * @Route("/articles/{slug}/like/{type<like|dislike>}", methods={"POST"}, name="app_article_like")
     * @param Article $article
     * @param $type
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function like(Article $article, $type, EntityManagerInterface $em): string
    {
        if ($type === 'like') {
            $article->setVote();
        } else {
            $article->pickUpVote();
        }
        
        $em->flush();
        
        return $this->json(['likes' => $article->getVoteCount()]);
    }
}
