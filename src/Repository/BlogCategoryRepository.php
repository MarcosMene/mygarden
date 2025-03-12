<?php

namespace App\Repository;

use App\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogCategory>
 */
class BlogCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }

    /**
     * @return Categories[]
     */
    public function findAllOrderedByAppearCategory()
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
