<?php

namespace App\Repository;

use App\Entity\Employees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Employees>
 */
class EmployeesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employees::class);
    }


    //FIND EMPLOYEES
    public function searchEmployees(string $query = ''): Query
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.lastName LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.joinDate', 'DESC');

        return $queryBuilder->getQuery();
    }
}
