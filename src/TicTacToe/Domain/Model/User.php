<?php

declare(strict_types=1);

namespace TicTacToe\Domain\Model;

use Assert\Assertion;

class User
{
    /** @var UserId */
    private $id;

    /** @var string */
    private $username;

    /** @var \DateTime */
    private $createdAt;

    private function __construct(UserId $userId, string $username, \DateTime $createdAt)
    {
        Assertion::betweenLength($username, 4, 16);

        $this->id = $userId;
        $this->username = $username;
        $this->createdAt = $createdAt;
    }

    public static function create(UserId $userId, string $username) : self
    {
        return new self($userId, $username, new \DateTime());
    }

    public function id() : UserId
    {
        return $this->id;
    }

    public function username() : string
    {
        return $this->username;
    }
}