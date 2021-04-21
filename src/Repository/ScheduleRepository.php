<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

/**
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @param array $data
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrUpdateSchedule(string $from, string $to, array $data): void
    {
        $schedule = $this->findOneBy(['station_from' => $from, 'station_to' => $to]);

        if ($schedule === null) {
            $schedule = (new Schedule())
                ->setStationFrom($from)
                ->setStationTo($to);
        }

        $schedule->setData($data);

        $this->getEntityManager()->persist($schedule);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $stationId
     *
     * @return array
     */
    public function findAllByStationId(string $stationId): array
    {
        $qb = $this->createQueryBuilder('s');

        $qb->andWhere('s.station_from = :station OR s.station_to = :station')
            ->setParameter('station', $stationId);

        return $qb->getQuery()->getResult();
    }
}
