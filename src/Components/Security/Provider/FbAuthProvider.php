<?php

declare(strict_types=1);

namespace App\Components\Security\Provider;

use App\Components\Security\Authenticator\FbAuthenticator;
use App\Components\Security\DTO\FbUserDTO;
use Facebook\Facebook;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Exception;

class FbAuthProvider implements FbAuthProviderInterface
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
            $fb = new Facebook([
                'app_id' => $this->getAppId(),
                'app_secret' => $this->getAppSecret(),
            ]);

            return $fb->getOAuth2Client()->getAuthorizationUrl(
                $this->getRedirectUri(),
                FbAuthenticator::STATE_CODE,
                ['email']
            );
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return '';
        }
    }

    /**
     * @param string $code
     *
     * @return FbUserDTO|null
     */
    public function getUser(string $code): ?FbUserDTO
    {
        try {
            $fb = new Facebook([
                'app_id' => $this->getAppId(),
                'app_secret' => $this->getAppSecret(),
            ]);

            $oauth = $fb->getOAuth2Client();

            $accessToken = $oauth->getAccessTokenFromCode($code, $this->getRedirectUri());

            $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);

            $user = $response->getGraphUser();

            if ($user) {
                $dto = new FbUserDTO();
                $dto->setId($user->getId())
                    ->setEmail($user->getEmail())
                    ->setName($user->getName());

                $picture = $user->getPicture();

                if ($picture) {
                    $dto->setPhoto($picture->getUrl());
                }

                return $dto;
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
    private function getAppId(): int
    {
        return (int) $this->parameters->get('app.fb.app_id');
    }

    /**
     * @return string
     */
    private function getAppSecret(): string
    {
        return (string) $this->parameters->get('app.fb.app_secret');
    }
}