<?php

declare(strict_types=1);

namespace App\Repository;

use App\Components\Admin\Service\Pager\Paginator;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param Request $request
     * @param int $max
     *
     * @return Paginator
     */
    public function getPaginatedFilteredArticles(Request $request, int $max = 100): Paginator
    {
        $page = $request->query->getInt('page', 1);

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC');

        return new Paginator($qb->getQuery(), $page, $max);
    }
}
