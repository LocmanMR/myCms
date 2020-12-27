<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Service\ArticleProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use DateTime;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     * @param EntityManagerInterface $em
     * @param ArticleProvider $articleProvider
     * @return Response
     * @throws Exception
     */
    public function create(EntityManagerInterface $em, ArticleProvider $articleProvider): Response
    {
        $article = new Article();
        $article
            ->setTitle('Есть ли жизнь после девятой жизни?')
            ->setSlug('article-' . random_int(100, 999))
            ->setDescription('kitty story')
            ->setBody($articleProvider->getArticleContent())
            ->setAuthor('IRR')
            ->setKeywords('kitty')
            ->setVoteCount(random_int(0, 10))
            ->setImageFilename('article-1.jpeg')
        ;

        if (random_int(1, 10) > 4) {
            $article->setPublishedAt(new DateTime(sprintf('-%d days', random_int(0, 100))));
        }

        $em->persist($article);
        $em->flush();

        return new Response(sprintf(
            'A new article has been created: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }
}
