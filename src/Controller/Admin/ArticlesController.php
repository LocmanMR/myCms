<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     * @IsGranted("ROLE_ADMIN")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        return new Response('ToDo');
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
