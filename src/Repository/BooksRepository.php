<?php

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Books::class);
    }

    /**
     * @param $value
     * @return Books|null
     */
    public function findById($value)
    {
        return $this->find($value);
    }


    /**
     * @param int $page
     * @param int $limit
     * @return array|null
     */
    public function getAllBooks(int $page, int $limit) :?array
    {
        return $this->createQueryBuilder('b')
            ->getQuery()
            ->setFirstResult($page)
            ->setMaxResults($limit)
            ->getArrayResult();
    }
}
