<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use App\Enum\Dictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findAllWithSearchQuery(?string $search, bool $withSoftDeleted = false): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');

        if ($search) {
            $qb
                ->andWhere('t.name LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        if ($withSoftDeleted) {
            $this->getEntityManager()->getFilters()->disable(Dictionary::DOCTRINE_DELETABLE_FILTER);
        }

        return $qb
            ->innerJoin('t.articles', 'a')
            ->addSelect('a')
            ->orderBy('t.createdAt', 'DESC')
            ;
    }
}
