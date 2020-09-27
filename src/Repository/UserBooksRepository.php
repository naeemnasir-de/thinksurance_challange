<?php

namespace App\Repository;

use App\Entity\Books;
use App\Entity\UserBooks;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBooks[]    findAll()
 * @method UserBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBooks::class);
    }


    /**
     * @param int $userId
     * @param int $bookId
     * @return UserBooks|null
     */
    public function findOneBySomeField(int $userId, int $bookId): ?UserBooks
    {
        return $this->findOneBy(['user_id' => $userId, 'book_id' => $bookId]);
    }

    /**
     * @param Users $user
     * @param Books $book
     * @return bool
     */
    public function save(Users $user, Books $book) :bool
    {
        try{
            $userBooks = new UserBooks;
            $userBooks->setUserId($user);
            $userBooks->setBookId($book);
            $this->_em->persist($userBooks);
            $this->_em->flush($userBooks);
        } catch (\Doctrine\ORM\ORMException | \Doctrine\ORM\OptimisticLockException $ex){
            return false;
        }

        return true;

    }
}
