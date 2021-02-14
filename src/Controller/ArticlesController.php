<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return new Response('ToDo');
    }
}
