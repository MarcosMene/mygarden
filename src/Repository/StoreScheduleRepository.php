<?php

namespace App\Repository;

use App\Entity\StoreSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StoreSchedule>
 */
class StoreScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreSchedule::class);
    }

    /**
     * get  all store schedules order by dayOrder
     */
    public function findAllOrderedByDayOrder()
    {
        return $this->findBy([], ['dayOrder' => 'ASC']);
    }
}