<?php
/**
 * Created by PhpStorm.
 * User: naeem
 * Date: 27.09.20
 * Time: 17:23
 */

namespace App\Tests\BusinessLogic\BooksKeeping\Users;


use App\BusinessLogic\BooksKeeping\Users\UsersManagerService;
use App\Entity\Users;
use App\Repository\UsersRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UsersManagerServiceTest extends TestCase
{
    /**
     * @var UsersRepository | MockObject
     */
    private $repository;

    /**
     * @var UsersManagerService
     */
    private $instance;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(UsersRepository::class);

        $this->instance = new UsersManagerService(
            $this->repository
        );
    }

    public function testGetAllUsers(): void
    {
        $page = \random_int(10, 100);
        $limit = \random_int(10, 100);
        $result = ['users' => 'users'];

        $this->repository->expects(static::once())
            ->method('getAllUsers')
            ->with(static::equalTo($page), static::equalTo($limit))
            ->willReturn($result);

        static::assertEquals($result, $this->instance->getAllUsers($page, $limit));

    }


    public function testFindById(): void
    {
        $userId = \random_int(10, 100);
        $user = $this->createMock(Users::class);

        $this->repository->expects(static::once())
            ->method('findById')
            ->with(static::equalTo($userId))
            ->willReturn($user);

        static::assertInstanceOf(Users::class, $this->instance->findById($userId));

    }

}