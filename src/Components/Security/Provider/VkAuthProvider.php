<?php

declare(strict_types=1);

namespace App\Components\Security\Provider;

use App\Components\Security\Authenticator\VkAuthenticator;
use App\Components\Security\DTO\VkUserDTO;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;
use Exception;

class VkAuthProvider implements VkAuthProviderInterface
{
    /** @var ContainerBagInterface */
    private $parameters;

    /** @var LoggerInterface */
    private  $logger;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * @param ContainerBagInterface $containerBag
     * @param LoggerInterface $logger
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        ContainerBagInterface $containerBag,
        LoggerInterface $logger,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->parameters = $containerBag;
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return string
     */
    public function getAuthUrl(): string
    {
        try {
            $oauth = new VKOAuth();

            return $oauth->getAuthorizeUrl(
                VKOAuthResponseType::CODE,
                $this->getClientId(),
                $this->getRedirectUri(),
                VKOAuthDisplay::PAGE,
                [VKOAuthUserScope::EMAIL],
                VkAuthenticator::STATE_CODE
            );

        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return '';
        }
    }

    /**
     * @param string $code
     *
     * @return VkUserDTO|null
     */
    public function getUser(string $code): ?VkUserDTO
    {
        try {
            $oauth = new VKOAuth();

            $accessTokenResponse = $oauth->getAccessToken(
                $this->getClientId(),
                $this->getClientSecret(),
                $this->getRedirectUri(),
                $code
            );

            if (!isset($accessTokenResponse['access_token'], $accessTokenResponse['user_id'])) {
                throw new NotFoundHttpException('Access token not found in VK response');
            }

            $vk = new VKApiClient();

            $usersResponse = $vk->users()->get($accessTokenResponse['access_token'], [
                'user_ids'  => [$accessTokenResponse['user_id']],
                'fields'    => ['photo_50'],
            ]);

            if (is_array($usersResponse) && isset($usersResponse[0])) {
                $userResponse = $usersResponse[0];
                $userId = (string) $userResponse['id'];

                if ($userId) {
                    return (new VkUserDTO())
                        ->setId($userId)
                        ->setEmail($accessTokenResponse['email'] ?? null)
                        ->setFirstName($userResponse['first_name'] ?? null)
                        ->setLastName($userResponse['last_name'] ?? null)
                        ->setPhoto($userResponse['photo_50'] ?? null);
                }
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        return null;
    }

    /**
     * @return string
     */
    private function getRedirectUri(): string
    {
        return $this->urlGenerator->generate(
            'login',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @return int
     */
    private function getClientId(): int
    {
        return (int) $this->parameters->get('vk_client_id');
    }

    /**
     * @return string
     */
    private function getClientSecret(): string
    {
        return (string) $this->parameters->get('vk_client_secret');
    }
}