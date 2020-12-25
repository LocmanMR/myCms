<?php

namespace App\Controller\Admin;

use App\Entity\Article;
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
     * @return Response
     * @throws Exception
     */
    public function create(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article
            ->setTitle('Есть ли жизнь после девятой жизни?')
            ->setSlug('article-' . random_int(100, 999))
            ->setDescription('kitty story')
            ->setBody(<<<EOF
Lorem ipsum **красная точка** dolor sit amet, consectetur adipiscing elit, sed
do eiusmod tempor incididunt [Сметанка](/) ut labore et dolore magna aliqua.
Purus viverra accumsan in nisl. Diam vulputate ut pharetra sit amet aliquam. Faucibus a
pellentesque sit amet porttitor eget dolor morbi non. Est ultricies integer quis auctor
elit sed. Tristique nulla aliquet enim tortor at. Tristique et egestas quis ipsum. Consequat semper viverra nam
libero. Lectus quam id leo in vitae turpis. In eu mi bibendum neque egestas congue
quisque egestas diam. **Красная точка** blandit turpis cursus in hac habitasse platea dictumst quisque.
EOF
            )
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
