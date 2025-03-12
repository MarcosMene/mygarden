<?php

namespace App\Repository;

use App\Entity\BlogTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogTag>
 */
class BlogTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogTag::class);
    }

    /**
     * @return Tags[]
     */
    public function findAllOrderedByAppearTag()
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
