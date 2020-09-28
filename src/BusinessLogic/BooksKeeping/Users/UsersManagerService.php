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

/** The only responsibility of this class is to add, delete, list,update user
 *
 * Class UsersManagerService
 *
 * @package App\BusinessLogic\BooksKeeping\Users
 */
class UsersManagerService implements UsersManagerServiceInterface
{
    public const USER_ID = 'user_id';

    /**
     * @var UsersRepository
     */
    private $repository;


    /**
     * UsersManagerService constructor.
     *
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAllUsers(int $page, int $limit): array
    {
        return $this->repository->getAllUsers($page, $limit);
    }


    /**
     * @param $id
     *
     * @return Users|null
     */
    public function findById($id): ?Users
    {
        return $this->repository->findById($id);
    }
}