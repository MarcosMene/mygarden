<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllWithLimit()
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function findBestSellers()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isBestSeller = true')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function findPromotions()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPromotion = true')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }
    public function findNewArrivals()
    {
        $date = new \DateTime();
        $date->modify('-30 days'); // Products added in the last 30 days

        return $this->createQueryBuilder('p')
            ->where('p.createdAt >=:date')
            ->setParameter('date', $date)
            ->setMaxResults(12)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findTopRated()
    {
        return $this->createQueryBuilder('p')
            ->where('p.rating>=:rating')
            ->setParameter('rating', 4)
            ->getQuery()
            ->getResult();
    }
}
