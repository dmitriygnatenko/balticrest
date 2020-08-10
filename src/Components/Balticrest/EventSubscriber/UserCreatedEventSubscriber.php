<?php

declare(strict_types=1);

namespace App\Components\Balticrest\EventSubscriber;

use App\Components\Balticrest\Event\UserCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\User;
use Throwable;

class UserCreatedEventSubscriber implements EventSubscriberInterface
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
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::class => 'onUserCreated',
        ];
    }

    /**
     * @param UserCreatedEvent $event
     */
    public function onUserCreated(UserCreatedEvent $event)
    {
        $user = $event->getUser();

        if (
            $user->getIsConfirmed() === false &&
            $user->getFbId() === null &&
            $user->getVkId() === null &&
            $user->getIsActive() === true
        ) {
            // Отправляем письмо с ссылкой на завершение регистрации
            // $this->sendConfirmEmail($user);
        }

        $this->saveHistory($user);
    }

    /**
     * @param User $user
     */
    private function sendConfirmEmail(User $user)
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject('Test')
            ->htmlTemplate('balticrest/email/registration_confirm.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ]);

        try {
            $this->mailer->send($email);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }

    /**
     * @param User $user
     */
    private function saveHistory(User $user)
    {
        // TODO
    }
}