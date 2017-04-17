<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service;

use TicTacToe\Domain\Model\User;

trait UserResponseTrait
{
    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user(): User
    {
        return $this->user;
    }
}