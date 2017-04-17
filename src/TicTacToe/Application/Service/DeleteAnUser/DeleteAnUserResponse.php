<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service\DeleteAnUser;

class DeleteAnUserResponse
{
    /** @var int */
    private $numUsersAffected;

    public function __construct(int $numUsersAffected)
    {
        $this->numUsersAffected = $numUsersAffected;
    }

    public function numUsersAffected(): int
    {
        return $this->numUsersAffected;
    }
}