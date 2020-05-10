<?php

namespace App\Repository;

use App\Entity\PointLangData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PointLangData|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointLangData|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointLangData[]    findAll()
 * @method PointLangData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointLangDataRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointLangData::class);
    }
}
