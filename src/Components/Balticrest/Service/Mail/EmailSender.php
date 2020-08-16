<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mail;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Throwable;

class EmailSender implements EmailSenderInterface
{
    /** @var MailerInterface */
    private $mailer;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param MailerInterface $mailer
     * @param LoggerInterface $logger
     */
    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     */
    public function sendConfirmEmail(User $user)
    {
        // TODO

        // Генерируем ссылку на подтверждение адреса эл. почты

        try {

            $email = (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject('Test')
                ->htmlTemplate('balticrest/email/registration_confirm.html.twig')
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => 'foo',
                ]);

            $this->mailer->send($email);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}