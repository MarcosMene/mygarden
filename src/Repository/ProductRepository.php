<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

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
            ->setMaxResults(16)
            ->getQuery()
            ->getResult();
    }

    public function findBestSellers()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isBestSeller = true')
            ->setMaxResults(16)
            ->getQuery()
            ->getResult();
    }

    public function findPromotions()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPromotion = true')
            ->setMaxResults(16)
            ->getQuery()
            ->getResult();
    }
    public function findNewArrivals()
    {
        $date = new \DateTime();
        $date->modify('-30 days'); // PRODUCTS ADDED IN THE LAST 30 DAYS

        return $this->createQueryBuilder('p')
            ->where('p.createdAt >=:date')
            ->setParameter('date', $date)
            ->setMaxResults(16)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findTopRated($limit = 16)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.reviews', 'r') // Join Product with Review
            ->where('r.rate >= :rating') // Filter products with rating >= 5
            ->andWhere('r.isApproved = :approved') // Only approved reviews
            ->setParameter('rating', 5)
            ->setParameter('approved', true)
            ->groupBy('p.id') // Group by product
            ->orderBy('r.rate', 'DESC') // Sort by rating
            ->setMaxResults($limit) // Limit to 12 products
            ->getQuery()
            ->getResult();
    }


    public function findOtherSuggestions($product)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id != :id')
            ->andWhere('p.isSuggestion = true')
            ->setParameter('id', $product->getId())
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }


    //FIND PRODUCTS
    public function searchProducts(string $query = ''): Query
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.createdAt', 'DESC');

        return $queryBuilder->getQuery();
    }
}
