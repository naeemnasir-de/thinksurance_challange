<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 22:34
 */

namespace App\BusinessLogic\BooksKeeping\Users;


use App\Entity\Users;
use App\Repository\UsersRepository;

class UsersManagerService implements UsersManagerServiceInterface
{

    /**
     * @var UsersRepository
     */
    private $repository;

    public function __construct(UsersRepository $repository)
{
    $this->repository = $repository;
}

    public function getAllUsers(int $page, int $limit): array
    {
        return $this->repository->getAllUsers($page, $limit);
    }


    public function findById($id) :?Users
    {
        return $this->repository->findById($id);
    }
}