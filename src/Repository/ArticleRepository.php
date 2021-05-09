<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use App\Enum\Dictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findLatestPublished(): array
    {
        return $this->published($this->latest())
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->leftJoin('a.tags', 't')
            ->addSelect('t')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findLatest(): array
    {
        return $this->latest()
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findPublished(): array
    {
        return $this->published()
            ->getQuery()
            ->getResult()
            ;
    }

    private function published(QueryBuilder $qb = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.publishedAt IS NOT NULL')
            ;
    }

    public function latest(QueryBuilder $qb = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb)->orderBy('a.publishedAt', 'DESC');
    }

    /**
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?? $this->createQueryBuilder('a');
    }

    public function searchArticles(?string $search, bool $withSoftDeleted = false): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb
                ->leftJoin('a.author', 'u')
                ->andWhere('a.body LIKE :search OR a.title LIKE :search OR u.firstName LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        if ($withSoftDeleted) {
            $this->getEntityManager()->getFilters()->disable(Dictionary::DOCTRINE_DELETABLE_FILTER);
        }

        return $qb
            ->orderBy('a.publishedAt', 'DESC')
        ;
    }
}
