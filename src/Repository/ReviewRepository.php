<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     *  @param int $productId
     * @return Review[]
     */
    public function findApprovedReviewsByProduct(int $productId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.product = :productId')
            ->andWhere('r.isApproved = :isApproved')
            ->setParameter('productId', $productId)
            ->setParameter('isApproved', true)
            ->orderBy('r.isApproved', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //FIND REVIEW PRODUCTS
    public function searchProductReviews(string $query = ''): Query
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.product', 'p') //join product table
            ->where('p.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.createdAt', 'DESC');

        return $queryBuilder->getQuery();
    }
}
