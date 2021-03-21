<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Article;
use App\Enum\UserRoles;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\ArticleContentProviderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Symfony\Component\Routing\Annotation\Route;

class ContentProviderController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $apiLoggerLogger;

    public function __construct(LoggerInterface $apiLoggerLogger)
    {
        $this->apiLoggerLogger = $apiLoggerLogger;
    }

    /**
     * @Route("/api/v1/article_content", name="article_content_provider")
     * @IsGranted("ROLE_API")
     * @param Request $request
     * @param ArticleContentProviderService $articleContentProvider
     * @return Response
     * @throws Exception
     */
    public function articleContentProvider
    (
        Request $request,
        ArticleContentProviderService $articleContentProvider
    ): Response {
        if (!$this->checkUserRole()) {
            return new JsonResponse(['message' => 'Access denied'], 403);
        }

        $paragraphs = $request->query->get('paragraphs');
        $word = $request->query->get('word');
        $wordCount = $request->query->get('wordCount');

        $articleContent = $articleContentProvider->get((int)$paragraphs, (string)$word, (int)$wordCount);

        return $this->json(['text' => $articleContent]);
    }

    /**
     * @Route("/api/v1/articles/{id}", name="article_info")
     * @IsGranted("API", subject="article")
     * @param Article $article
     * @return Response
     */
    public function articleContent(Article $article): Response
    {
        return $this->json([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'slug' => $article->getSlug(),
            'body' => $article->getBody(),
            'description' => $article->getDescription(),
            'keywords' => $article->getKeywords(),
            'votes' => $article->getVoteCount(),
            'published' => $article->getPublishedAt(),
        ]);
    }

    private function checkUserRole(): bool
    {
        $user = $this->getUser();

        if (!$this->isGranted(UserRoles::USER_ROLE_API, $user)) {
            $this->apiLoggerLogger->warning('User is trying to access', [
                'User' => $user->getUsername(),
                'Roles' => $user->getRoles(),
            ]);

            return false;
        }

        return true;
    }
}
