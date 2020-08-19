<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mail;

use App\Components\Balticrest\Service\Auth\UserConfirmCodeGeneratorInterface;
use App\Entity\User;
use App\Entity\UserConfirmCode;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTimeImmutable;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class EmailSender implements EmailSenderInterface
{
    /** @var MailerInterface */
    private $mailer;

    /** @var LoggerInterface */
    private $logger;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var TranslatorInterface */
    private $translator;

    /** @var UserConfirmCodeGeneratorInterface */
    private $userConfirmCodeGenerator;

    /**
     * @param MailerInterface $mailer
     * @param LoggerInterface $logger
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     * @param UserConfirmCodeGeneratorInterface $userConfirmCodeGenerator
     */
    public function __construct(
        MailerInterface $mailer,
        LoggerInterface $logger,
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator,
        UserConfirmCodeGeneratorInterface $userConfirmCodeGenerator
    ) {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->userConfirmCodeGenerator = $userConfirmCodeGenerator;
    }

    /**
     * @param User $user
     */
    public function sendConfirmEmail(User $user)
    {
        $confirmCode = $this->userConfirmCodeGenerator->generate($user);

        $confirmUrl = $this->urlGenerator->generate(
            'confirm',
            [
                'code' => $confirmCode->getCode()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $expirationTimestamp = $confirmCode->getCreated()->getTimestamp() + UserConfirmCode::EXPIRATION_TIME;

        $expirationDate = (new DateTimeImmutable())->setTimestamp($expirationTimestamp);

        try {
            $email = (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject($this->translator->trans('confirm.subject', [], 'email'))
                ->htmlTemplate('balticrest/email/registration_confirm.html.twig')
                ->context([
                    'expiration_date' => $expirationDate,
                    'confirm_url' => $confirmUrl,
                ]);

            $this->mailer->send($email);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}