<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service\GetAnUser;

use TicTacToe\Application\Service\UserServiceAbstract;

final class GetAnUserService extends UserServiceAbstract
{
    public function execute(GetAnUserRequest $request) : GetAnUserResponse
    {
        try {
            $anUser = $this->userRepository->findByUsername($request->username());
            if (! $anUser) {
                throw new \Exception('User ' . $request->username() . ' not exist. Please create a new user');
            }

            return new GetAnUserResponse($anUser);
        } catch (\Throwable $t) {
            throw new GetAnUserException($t->getMessage());
        }
    }
}