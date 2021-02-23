<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleVoteController extends AbstractController
{
    /**
     * @Route("/articles/{slug}/vote/{type<up|down>}", methods={"POST"}, name="app_article_vote")
     * @param Article $article
     * @param string $type
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function vote(Article $article, string $type, EntityManagerInterface $em): JsonResponse
    {
        if ($type === 'up') {
            $article->setVote();
        } else {
            $article->pickUpVote();
        }
        $em->flush();

        return $this->json(['voteCount' => $article->getVoteCount()]);
    }
}