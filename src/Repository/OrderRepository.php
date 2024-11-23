<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * show orders from user's member space
     *
     */
    public function findSuccessOrders($user)
    {
        return $this->createQueryBuilder('o') //order
            ->andWhere('o.state > 1')
            ->andWhere('o.user = :user') //show only orders from connected user
            ->setParameter('user', $user)
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedCreatedDesc()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
