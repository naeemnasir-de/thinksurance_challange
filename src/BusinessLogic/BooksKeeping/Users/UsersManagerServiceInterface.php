<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 22:34
 */

namespace App\BusinessLogic\BooksKeeping\Users;


interface UsersManagerServiceInterface
{
    public function getAllUsers(int $page, int $limit) :array;

}