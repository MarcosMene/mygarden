<?php

namespace App\Repository;

use App\Entity\Header;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Header>
 */
class HeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Header::class);
    }


    /**
     * @return Header[]
     */
    public function findAllOrderedByAppear()
    {
        return $this->createQueryBuilder('h')
        ->orderBy('h.orderAppear', 'ASC')
        ->getQuery()
        ->getResult();
    }
}
