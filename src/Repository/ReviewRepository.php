<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

}
