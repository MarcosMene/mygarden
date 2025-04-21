<?php

namespace App\Repository;

use App\Entity\ReviewClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<ReviewClient>
 */
class ReviewClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewClient::class);
    }


    //FIND REVIEW CLIENTS
    public function searchStoreReviews(?string $query = null): Query
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.user', 'u');

        if ($query !== null && $query !== '') {
            $normalized = strtolower(trim($query));

            if (is_numeric($normalized) && in_array((int)$normalized, [1, 2, 3, 4, 5])) {
                $qb->andWhere('r.rate = :rate')
                    ->setParameter('rate', (int)$normalized);
            } elseif (in_array($normalized, ['yes', 'no'])) {
                $isApproved = $normalized === 'yes';
                $qb->andWhere('r.isApproved = :approved')
                    ->setParameter('approved', $isApproved);
            } else {
                $qb->andWhere('LOWER(u.lastname) LIKE :lastname')
                    ->setParameter('lastname', '%' . $normalized . '%');
            }
        }

        $qb->orderBy('r.createdAt', 'DESC');

        return $qb->getQuery();
    }
}
