<?php

declare(strict_types=1);

namespace App\Components\Balticrest\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Email;

class MailerEventSubscriber implements EventSubscriberInterface
{
    /** @var ContainerBagInterface */
    private $parameters;

    /**
     * @param ContainerBagInterface $containerBag
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->parameters = $containerBag;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'onMessage',
        ];
    }

    /**
     * @param MessageEvent $event
     */
    public function onMessage(MessageEvent $event): void
    {
        /** @var Email $email */
        $email = $event->getMessage();

        $email->from($this->getFrom());
    }

    /**
     * @return string
     */
    private function getFrom(): string
    {
        return $this->parameters->get('app.mailer_from');
    }
}