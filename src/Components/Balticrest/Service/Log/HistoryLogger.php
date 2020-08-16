<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Log;

use App\Entity\History;
use App\Entity\Interfaces\HistoryTypesInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;
use Throwable;

class HistoryLogger implements HistoryLoggerInterface, HistoryTypesInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var EntityManager */
    private $entityManager;

    /** @var Security */
    private $security;

    /**
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, Security $security)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param int $type
     * @param array $context
     * @param User|null $user
     */
    public function log(int $type, array $context = [], ?User $user = null): void
    {
        if ($user === null) {
            $user = $this->security->getUser();
        }

        try {
            $history = (new History())
                ->setUser($user)
                ->setType($type)
                ->setContext($context);

            $this->entityManager->persist($history);

            $this->entityManager->flush();
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}