<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
        return $this->createQueryBuilder('o') //ORDER
            ->andWhere('o.state > 1')
            ->andWhere('o.user = :user') //SHOW ONLY ORDERS FROM CONNECTED USER
            ->setParameter('user', $user)
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function searchOrdersByUserOrOrderId(string $query = ''): Query
    {
        $queryBuilder = $this->createQueryBuilder('o') // 'o' for order
            ->join('o.user', 'u') // join user
            ->orderBy('o.createdAt', 'DESC'); // order by id

        if ($query) {
            $queryBuilder->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('u.lastname', ':query'), // search lastname user
                    $queryBuilder->expr()->like('o.id', ':query') // search id order
                )
            )
                ->setParameter('query', '%' . $query . '%');
        }

        return $queryBuilder->getQuery();
    }
}
