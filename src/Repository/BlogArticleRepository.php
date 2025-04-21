<?php

namespace App\Repository;

use App\Entity\BlogArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogArticle>
 */
class BlogArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogArticle::class);
    }

    // //RETURN A QUERYBUILDER TO ALL ARTICLES
    // public function findAllQuery(): QueryBuilder
    // {
    //     return $this->createQueryBuilder('a')
    //         ->orderBy('a.createdAt', 'DESC');
    // }

    //RETURN A QUERYBUILDER TO TITLE
    public function searchArticles(string $query = ''): Query
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.title LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.createdAt', 'DESC');

        return $queryBuilder->getQuery();
    }

    public function findLatestArticles($offset, $limit)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findCategories(): array
    {
        return $this->createQueryBuilder('a')
            ->select('DISTINCT c.name AS category')
            ->join('a.category', 'c') //MAKE JOIN WITH ENTITY CATEGORY
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult(); // RETURNS AN ASSOCIATIVE ARRAY
    }

    public function findYears()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT DISTINCT YEAR(created_at) AS year FROM blog_article ORDER BY year DESC';

        return $conn->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * find article by title, category and year
     */
    public function findByFilters(?string $name, ?string $category, ?string $year, int $offset = 0, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.category', 'c') // RELATIONSHIP WITH THE CATEGORY ENTITY
            ->addSelect('c')
            ->orderBy('b.createdAt', 'DESC') // STANDARD SORTING BY DATE
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // FILTER BY NAME OF ARTICLE
        if (!empty($name)) {
            $qb->andWhere('b.title LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        // FILTER BY CATEGORY (NEED TO ENSURE THAT CATEGORY IS HANDLED CORRECTLY)
        if (!empty($category)) {
            $qb->andWhere('c.name = :category')
                ->setParameter('category', $category);
        }

        //FILTER BY YEAR USING BETWEEN
        if (!empty($year)) {
            $startDate = new \DateTime($year . '-01-01 00:00:00');
            $endDate = new \DateTime($year . '-12-31 23:59:59');

            $qb->andWhere('b.createdAt BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate);
        }

        return $qb->getQuery()->getResult();
    }
}