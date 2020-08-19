<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserConfirmCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserConfirmCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserConfirmCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserConfirmCode[]    findAll()
 * @method UserConfirmCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConfirmCodeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserConfirmCode::class);
    }
}
