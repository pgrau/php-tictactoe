<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service;

use TicTacToe\Domain\Model\UserRepository;

abstract class UserServiceAbstract
{
    /** @var UserRepository */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}