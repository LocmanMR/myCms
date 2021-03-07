<?php
declare(strict_types=1);

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var ApiTokenRepository
     */
    private ApiTokenRepository $apiTokenRepository;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $apiLoggerLogger;

    /**
     * ApiTokenAuthenticator constructor.
     * @param ApiTokenRepository $apiTokenRepository
     * @param LoggerInterface $apiLoggerLogger
     */
    public function __construct(ApiTokenRepository $apiTokenRepository, LoggerInterface $apiLoggerLogger)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->apiLoggerLogger = $apiLoggerLogger;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization') && 0 === strpos(
                $request->headers->get('Authorization'),
                'Bearer'
            );
    }

    public function getCredentials(Request $request): string
    {
        return substr($request->headers->get('Authorization'), 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepository->findOneBy(['token' => $credentials]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Invalid token');
        }

        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token expired');
        }

        $user = $token->getUser();
        if (!$user->getIsActive()) {
            throw new CustomUserMessageAuthenticationException('The user is inactive, contact the administration');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): void
    {
        $routeName = $request->attributes->get('_route');
        $fullUrl = $request->getSchemeAndHttpHost() . $request->getPathInfo();

        $this->apiLoggerLogger->info('User logged in', [
            'route' => $routeName,
            'url' => $fullUrl,
            'token' => $this->getCredentials($request),
            'user' => $token->getUser()->getUsername(),
        ]);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // Never Called
    }

    public function supportsRememberMe()
    {
        // Never Called
    }
}
