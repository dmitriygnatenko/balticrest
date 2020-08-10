<?php

declare(strict_types=1);

namespace App\Components\Security\Authenticator;

use App\Components\Balticrest\Service\Auth\UserCreatorInterface;
use App\Components\Security\DTO\FbUserDTO;
use App\Components\Security\Provider\FbAuthProviderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class FbAuthenticator extends AbstractGuardAuthenticator implements AuthenticatorInterface
{
    use TargetPathTrait;

    /** @var string */
    public const STATE_CODE = 'fb_code';

    /** @var Logger */
    private $logger;

    /** @var EntityManager */
    private $entityManager;

    /** @var FbAuthProviderInterface */
    private $fbAuthProvider;

    /** @var Translator */
    private $translator;

    /** @var UrlGenerator */
    private $urlGenerator;

    /** @var UserCreatorInterface */
    private $userCreator;

    /**
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param FbAuthProviderInterface $fbAuthProvider
     * @param UserCreatorInterface $userCreator
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        FbAuthProviderInterface $fbAuthProvider,
        UserCreatorInterface $userCreator,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->fbAuthProvider = $fbAuthProvider;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->userCreator = $userCreator;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return
            self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('GET')
            && $request->query->get('state') === self::STATE_CODE
            && $request->query->has('code');
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $code = $request->query->get('code');

        return $this->fbAuthProvider->getUser($code) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getUser($dto, UserProviderInterface $userProvider)
    {
        /** @var FbUserDTO $dto */
        if ($dto === false) {
            throw new CustomUserMessageAuthenticationException(
                $this->translator->trans('login.fb_incorrect_response', [], 'validators')
            );
        }

        // Поиск по FB ID
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['fb_id' => $dto->getId()]);

        if ($user) {
            if ($user->getIsActive() === false) {
                throw new CustomUserMessageAuthenticationException(
                    $this->translator->trans('login.blocked', [], 'validators')
                );
            }

            if ($user->getIsConfirmed() === false) {
                throw new CustomUserMessageAuthenticationException(
                    $this->translator->trans('login.not_confirmed', [], 'validators')
                );
            }

            return $user;
        }

        // Поиск по эл. почте
        if ($dto->getEmail()) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $dto->getEmail()]);
            if ($user) {
                if ($user->getIsActive() === false) {
                    throw new CustomUserMessageAuthenticationException(
                        $this->translator->trans('login.blocked', [], 'validators')
                    );
                }

                if ($user->getIsConfirmed() === false) {
                    throw new CustomUserMessageAuthenticationException(
                        $this->translator->trans('login.not_confirmed', [], 'validators')
                    );
                }

                if ($user->getFbId() !== null) {
                    // Пользователь с эл. почтой но другим FB ID
                    throw new CustomUserMessageAuthenticationException(
                        $this->translator->trans('login.user_create', [], 'validators')
                    );
                }

                $user->setFbId($dto->getId());

                try {
                    $this->entityManager->persist($user);
                    $this->entityManager->flush($user);
                } catch (Throwable $exception) {
                    $this->logger->error($exception->getMessage(), ['exception' => $exception]);

                    throw new CustomUserMessageAuthenticationException(
                        $this->translator->trans('login.user_create', [], 'validators')
                    );
                }

                return $user;
            }
        }

        if ($user === null) {
            $user = $this->userCreator->createFbUser($dto);

            if ($user === null) {
                throw new CustomUserMessageAuthenticationException(
                    $this->translator->trans('login.user_create', [], 'validators')
                );
            }
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($dto, UserInterface $user)
    {
        /** @var User $user */
        /** @var FbUserDTO $dto */
        return $user->getFbId() === $dto->getId();
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->getLoginUrl());
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            $this->removeTargetPath($request->getSession(), $providerKey);
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('main'));
    }

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }
}