<?php
declare(strict_types=1);

namespace App\API;

use App\Enum\UserRoles;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\ArticleContentProviderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

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
     * @IsGranted("ROLE_API")
     * @param Request $request
     * @param ArticleContentProviderService $articleContentProvider
     * @return Response
     * @throws Exception
     */
    public function articleContent(Request $request, ArticleContentProviderService $articleContentProvider): Response
    {
        if (!$this->checkUserRole()) {
            return new JsonResponse(['message' => 'Access denied'], 403);
        }

        $paragraphs = $request->query->get('paragraphs');
        $word = $request->query->get('word');
        $wordCount = $request->query->get('wordCount');

        $articleContent = $articleContentProvider->get((int)$paragraphs, (string)$word, (int)$wordCount);

        return $this->json(['text' => $articleContent]);
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
