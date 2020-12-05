<?php


namespace App\API;


use App\Service\ArticleContentProviderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentProviderController extends AbstractController
{
    /**
     * @param Request $request
     * @param ArticleContentProviderService $articleContentProvider
     * @return Response
     */
    public function articleContent(Request $request, ArticleContentProviderService $articleContentProvider): Response
    {
        $paragraphs = $request->query->get('paragraphs');
        $word = $request->query->get('word');
        $wordCount = $request->query->get('wordCount');

        $articleContent = $articleContentProvider->get((int)$paragraphs, (string)$word, (int)$wordCount);

        return $this->json(['text' => $articleContent]);
    }
}