<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    private const DOCTRINE_DELETABLE_FILTER = 'softdeletable';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findAllWithSearch(?string $search, bool $withSoftDeleted = false): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($search) {
            $qb
                ->andWhere('c.content LIKE :search OR c.authorName LIKE :search OR a.title LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        if ($withSoftDeleted) {
            $this->getEntityManager()->getFilters()->disable(self::DOCTRINE_DELETABLE_FILTER);
        }

        return $qb
            ->innerJoin('c.article', 'a')
            ->addSelect('a')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getLatestComments(int $commentsCount = 3): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->innerJoin('c.article', 'a')
            ->andWhere('a.publishedAt IS NOT NULL')
            ->andWhere('c.deletedAt IS NULL')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($commentsCount)
            ->getQuery()
            ->getResult()
            ;
    }
}
