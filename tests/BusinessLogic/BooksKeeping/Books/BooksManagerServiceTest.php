<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 27.09.20
 * Time: 16:23
 */

namespace App\Tests\BusinessLogic\BooksKeeping\Books;

use App\BusinessLogic\BooksKeeping\Books\BooksManagerService;
use App\BusinessLogic\BooksKeeping\Users\UsersManagerService;
use App\Entity\Books;
use App\Entity\UserBooks;
use App\Entity\Users;
use App\Repository\BooksRepository;
use App\Repository\UserBooksRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Throwable;

class BooksManagerServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $lastErrorMessage;
    /**
     * @var BooksRepository|MockObject
     */
    private $booksRepository;
    /**
     * @var UsersManagerService|MockObject
     */
    private $usersManagerService;
    /**
     * @var UserBooksRepository|MockObject
     */
    private $userBooksRepository;

    /**
     * @var BooksManagerService
     */
    private $instance;

    public function setUp(): void
    {
        parent::setUp();

        $this->userBooksRepository = $this->createMock(UserBooksRepository::class);
        $this->usersManagerService = $this->createMock(UsersManagerService::class);
        $this->booksRepository = $this->createMock(BooksRepository::class);

        $this->instance = new BooksManagerService(
            $this->booksRepository,
            $this->usersManagerService,
            $this->userBooksRepository
        );

    }

    /**
     * @covers \App\BusinessLogic\BooksKeeping\Books\BooksManagerService::purchaseBook
     *
     * @throws \Exception
     */
    public function testPurchaseBookWithBookNotFound(): void
    {
        $bookId = random_int(10, 100);
        $userId = random_int(10, 100);
        $this->booksRepository->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($bookId))
            ->willReturn(null);

        static::assertFalse($this->instance->purchaseBook($bookId, $userId));
    }

    /**
     * @covers \App\BusinessLogic\BooksKeeping\Books\BooksManagerService::purchaseBook
     *
     * @throws \Exception
     */
    public function testPurchaseBookWithUserNotFound(): void
    {
        $bookId = random_int(10, 100);
        $userId = random_int(10, 100);
        $book = $this->createMock(Books::class);

        $this->booksRepository->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($bookId))
            ->willReturn($book);

        $this->usersManagerService->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($userId))
            ->willReturn(null);

        static::assertFalse($this->instance->purchaseBook($bookId, $userId));
    }


    /**
     * @covers \App\BusinessLogic\BooksKeeping\Books\BooksManagerService::purchaseBook
     *
     * @throws \Exception
     */
    public function testPurchaseBookWithBookAlreadyPurchasedBySameUser(): void
    {
        $bookId = random_int(10, 100);
        $userId = random_int(10, 100);
        $book = $this->createMock(Books::class);
        $user = $this->createMock(Users::class);
        $userBooks = $this->createMock(UserBooks::class);
        $criteria = ['user_id' => $userId, 'book_id' => $bookId];

        $this->booksRepository->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($bookId))
            ->willReturn($book);

        $this->usersManagerService->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($userId))
            ->willReturn($user);

        $this->userBooksRepository->expects(static::once())
            ->method('findOneBy')
            ->with(static::equalTo($criteria))
            ->willReturn($userBooks);

        static::assertFalse($this->instance->purchaseBook($bookId, $userId));
    }


    /**
     * @covers \App\BusinessLogic\BooksKeeping\Books\BooksManagerService::purchaseBook
     *
     * @throws \Exception
     */
    public function testPurchaseBook(): void
    {
        $bookId = random_int(10, 100);
        $userId = random_int(10, 100);
        $book = $this->createMock(Books::class);
        $user = $this->createMock(Users::class);
        $criteria = ['user_id' => $userId, 'book_id' => $bookId];

        $this->booksRepository->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($bookId))
            ->willReturn($book);

        $this->usersManagerService->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($userId))
            ->willReturn($user);

        $this->userBooksRepository->expects(static::once())
            ->method('findOneBy')
            ->with(static::equalTo($criteria))
            ->willReturn(null);

        static::assertTrue($this->instance->purchaseBook($bookId, $userId));
    }


    /**
     * @covers \App\BusinessLogic\BooksKeeping\Books\BooksManagerService::getAllBooks
     *
     * @throws \Exception
     */
    public function testgetAllBooks(): void
    {
        $result = ['book' => 'book'];
        $page = random_int(10, 100);
        $limit = random_int(10, 100);
        $this->booksRepository->expects(static::once())
            ->method('getAllBooks')
            ->with(static::equalTo($page), static::equalTo($limit))
            ->willReturn($result);

        static::assertEquals($result, $this->instance->getAllBooks($page, $limit));
    }


    public function testGetLastErrorMessage(): void
    {
        $errorMessage = \uniqid('error', true);
        $this->instance->setLastErrorMessage($errorMessage);
        static::assertEquals($errorMessage, $this->instance->getLastErrorMessage());

    }

}