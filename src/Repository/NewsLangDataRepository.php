<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NewsLangData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsLangData|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsLangData|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsLangData[]    findAll()
 * @method NewsLangData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsLangDataRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsLangData::class);
    }
}
