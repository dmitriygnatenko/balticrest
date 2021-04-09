<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SearchData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchData[]    findAll()
 * @method SearchData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchDataRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchData::class);
    }

    /**
     * @param string $searchText
     *
     * @return array
     */
    public function search(string $searchText): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('MATCH_AGAINST(s.data) AGAINST(:search) > 0')
            ->setParameter('search', $searchText);

        return $qb->getQuery()->getResult();
    }
}
