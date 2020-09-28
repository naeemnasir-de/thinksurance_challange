<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 22:32
 */

namespace App\BusinessLogic\BooksKeeping\Books;


interface BooksManagerServiceInterface
{
    public function getAllBooks(int $page, int $limit): array;


    /**
     * @param int $bookId
     * @param int $userId
     *
     * @return bool
     */
    public function purchaseBook(int $bookId, int $userId): bool;

}