<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseArticleController extends AbstractController
{

    /**
     * @Route("/", name="app_base.html.twig")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('base.html.twig');
    }
}