<?php

declare(strict_types=1);

namespace App\Components\Balticrest\EventSubscriber;

use App\Components\Balticrest\Event\UserCreatedEvent;
use App\Components\Balticrest\Service\Log\HistoryLogger;
use App\Components\Balticrest\Service\Log\HistoryLoggerInterface;
use App\Components\Balticrest\Service\Mail\EmailSenderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\User;

class UserCreatedEventSubscriber implements EventSubscriberInterface
{
    /** @var HistoryLoggerInterface */
    private $historyLogger;

    /** @var EmailSenderInterface */
    private $emailSender;

    /**
     * @param HistoryLoggerInterface $historyLogger
     * @param EmailSenderInterface $emailSender
     */
    public function __construct(HistoryLoggerInterface $historyLogger, EmailSenderInterface $emailSender)
    {
        $this->historyLogger = $historyLogger;
        $this->emailSender = $emailSender;
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
            $this->emailSender->sendRegistrationConfirmEmail($user);
        }

        $this->saveHistory($user);
    }

    /**
     * @param User $user
     */
    private function saveHistory(User $user)
    {
        $context = [];

        if ($user->getVkId()) {
            $context['type'] = 'ВКонтакте';
        } else if ($user->getFbId()) {
            $context['type'] = 'Facebook';
        } else {
            $context['type'] = 'Логин/Пароль';
        }

        $this->historyLogger->log(HistoryLogger::USER_ADDED, $context, $user);
    }
}