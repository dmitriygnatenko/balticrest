<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\MessageHandler;

use App\Components\Balticrest\Service\Message\EmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

class EmailMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    private LoggerInterface $logger;

    /**
     * @param MailerInterface $mailer
     * @param LoggerInterface $logger
     */
    public function __construct(MailerInterface $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param EmailMessage $message
     */
    public function __invoke(EmailMessage $message)
    {
        try {
            $email = (new Email())
                ->to($message->getEmail())
                ->subject($message->getSubject())
                ->html($message->getContent());

            $this->mailer->send($email);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}