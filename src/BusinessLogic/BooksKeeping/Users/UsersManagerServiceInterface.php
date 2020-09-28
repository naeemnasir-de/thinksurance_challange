<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 22:34
 */

namespace App\BusinessLogic\BooksKeeping\Users;


use App\Entity\Users;

interface UsersManagerServiceInterface
{
    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAllUsers(int $page, int $limit): array;


    /**
     * @param $id
     *
     * @return Users|null
     */
    public function findById($id): ?Users;

}