<?php

namespace App\Entity;

use App\Repository\UserBooksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBooksRepository::class)
 */
class UserBooks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=books::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $book_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?users
    {
        return $this->user_id;
    }

    public function setUserId(?users $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBookId(): ?books
    {
        return $this->book_id;
    }

    public function setBookId(?books $book_id): self
    {
        $this->book_id = $book_id;

        return $this;
    }
}
