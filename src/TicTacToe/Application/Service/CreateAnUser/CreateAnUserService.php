<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service\CreateAnUser;

use TicTacToe\Application\Service\UserServiceAbstract;
use TicTacToe\Domain\Model\User;
use TicTacToe\Domain\Model\UserId;

final class CreateAnUserService extends UserServiceAbstract
{
    public function execute(CreateAnUserRequest $request) : CreateAnUserResponse
    {
        try {
            $anUser = User::create(UserId::create(), $request->username());
            $this->userRepository->persist($anUser);

            return new CreateAnUserResponse($anUser);
        } catch (\Throwable $t) {
            throw new CreateAnUserException($t->getMessage());
        }
    }
}