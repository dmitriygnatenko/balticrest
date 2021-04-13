<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

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
            ->where('MATCH_AGAINST(s.data) AGAINST(:search boolean) > 0')
            ->setParameter('search', $searchText);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @param int $typeId
     *
     * @return SearchData|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdAndType(int $id, int $typeId): ?SearchData
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.object_id = :id')
            ->andWhere('s.object_type_id = :type_id')
            ->setParameters([
                'id' => $id,
                'type_id' => $typeId
            ])
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @param int $typeId
     * @param string $data
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insert(int $id, int $typeId, string $data): void
    {
        $searchData = (new SearchData())
            ->setObjectId($id)
            ->setObjectTypeId($typeId)
            ->setData($data);

        $this->getEntityManager()->persist($searchData);
        $this->getEntityManager()->flush();
    }

    /**
     * @param SearchData $searchData
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(SearchData $searchData): void
    {
        $this->getEntityManager()->persist($searchData);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $ttlInDays
     */
    public function clean(int $ttlInDays): void
    {
        $lastUpdateTime = (new DateTimeImmutable())->modify('- ' . $ttlInDays . ' days');

        $qb = $this->createQueryBuilder('s')
            ->delete(SearchData::class, 's')
            ->where('s.last_update_time < :last_update_time')
            ->setParameter('last_update_time', $lastUpdateTime);

        $qb->getQuery()->execute();
    }
}
