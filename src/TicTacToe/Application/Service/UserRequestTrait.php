<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service;

trait UserRequestTrait
{
    /** @var string */
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function username() : string
    {
        return $this->username;
    }
}