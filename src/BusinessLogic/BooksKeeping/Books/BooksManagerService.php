<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 26.09.20
 * Time: 21:49
 */

namespace App\BusinessLogic\BooksKeeping\Books;

use App\BusinessLogic\BooksKeeping\Users\UsersManagerService;
use App\Entity\Books;
use App\Repository\BooksRepository;
use App\Repository\UserBooksRepository;
use App\Repository\UsersRepository;

/** The only responsibility of this class is to add, delete, list,update book
 *
 * Class BooksManagerService
 * @package App\BusinessLogic\BooksKeeping
 */
class BooksManagerService implements BooksManagerServiceInterface
{
    /**
     * @var string
     */
    private $lastErrorMessage;
    /**
     * @var BooksRepository
     */
    private $booksRepository;
    /**
     * @var UsersManagerService
     */
    private $usersManagerService;
    /**
     * @var UserBooksRepository
     */
    private $userBooksRepository;

    public function __construct(
        BooksRepository $booksRepository,
    UsersManagerService $usersManagerService,
    UserBooksRepository $userBooksRepository
    )
    {
        $this->booksRepository = $booksRepository;
        $this->usersManagerService = $usersManagerService;
        $this->userBooksRepository = $userBooksRepository;
    }

    /**
     * @return array
     */
    public function getAllBooks(int $page, int $limit) :array
    {
        return $this->booksRepository->getAllBooks($page, $limit);
    }


    public function purchaseBook(int $bookId, int $userId) :bool
    {
        $book = $this->findById($bookId);
        if ($book === null){
            $this->setLastErrorMessage(
                \sprintf("Book not found ID: %s", $bookId)
            );
            return false;
        }
        $user = $this->usersManagerService->findById($userId);

        if ($user === null){
            $this->setLastErrorMessage(
                \sprintf("User not found ID: %s", $userId)
            );
            return false;
        }

        $userBooks = $this->userBooksRepository->findOneBy(
            [
                'user_id' => $userId,
                'book_id' => $bookId,
            ]
        );

        if ($userBooks !== null){
            $this->setLastErrorMessage(
                \sprintf(
                    "User: %s already purchased: %s",
                    $user->getName(),
                    $book->getName()
                    )
            );
            return false;
        }


        $this->userBooksRepository->save($user, $book);

        return true;

    }

    /**
     * @return string
     */
    public function getLastErrorMessage(): string
    {
        return $this->lastErrorMessage;
    }

    /**
     * @param string $lastErrorMessage
     */
    public function setLastErrorMessage(string $lastErrorMessage): void
    {
        $this->lastErrorMessage = $lastErrorMessage;
    }


    private function findById($id) :?Books
    {
        return $this->booksRepository->findById($id);
    }

}