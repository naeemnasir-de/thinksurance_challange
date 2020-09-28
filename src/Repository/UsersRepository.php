<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }


    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAllUsers(int $page, int $limit)
    {
        return $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->getQuery()
            ->getArrayResult();
    }


    /**
     * @param $value
     *
     * @return Users|null
     */
    public function findById($value)
    {
        return $this->find($value);
    }
}
