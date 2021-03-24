<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mail;

use App\Components\Balticrest\Service\Auth\UserConfirmCodeGeneratorInterface;
use App\Components\Balticrest\Service\Message\EmailMessage;
use App\Entity\User;
use App\Entity\UserConfirmCode;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTimeImmutable;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use Exception;
use Twig\Environment;

class EmailSender implements EmailSenderInterface
{
    private LoggerInterface $logger;

    private UrlGeneratorInterface $urlGenerator;

    private TranslatorInterface $translator;

    private UserConfirmCodeGeneratorInterface $userConfirmCodeGenerator;

    private MessageBusInterface $bus;

    private Environment $twig;

    /**
     * @param LoggerInterface $logger
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     * @param UserConfirmCodeGeneratorInterface $userConfirmCodeGenerator
     * @param MessageBusInterface $bus
     * @param Environment $twig
     */
    public function __construct(
        LoggerInterface $logger,
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator,
        UserConfirmCodeGeneratorInterface $userConfirmCodeGenerator,
        MessageBusInterface $bus,
        Environment $twig
    ) {
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->userConfirmCodeGenerator = $userConfirmCodeGenerator;
        $this->bus = $bus;
        $this->twig = $twig;
    }

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function sendRestoreConfirmEmail(User $user): void
    {
        $confirmCode = $this->userConfirmCodeGenerator->generate($user, UserConfirmCode::TYPE_RESTORE_PASSWORD);

        $confirmUrl = $this->urlGenerator->generate(
            'restore_confirm',
            [
                'code' => $confirmCode->getCode()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $expirationTimestamp = $confirmCode->getCreated()->getTimestamp() + UserConfirmCode::EXPIRATION_TIME;

        $expirationDate = (new DateTimeImmutable())->setTimestamp($expirationTimestamp);

        try {
            $content = $this->twig->render('balticrest/email/restore_confirm.html.twig', [
                'expiration_date' => $expirationDate,
                'confirm_url' => $confirmUrl,
            ]);

            $emailMessage = (new EmailMessage())
                ->setEmail($user->getEmail())
                ->setSubject($this->translator->trans('restore.subject', [], 'email'))
                ->setContent($content);

            $this->bus->dispatch($emailMessage);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}