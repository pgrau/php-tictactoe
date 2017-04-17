<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service\DeleteAnUser;

use TicTacToe\Application\Service\UserServiceAbstract;

final class DeleteAnUserService extends UserServiceAbstract
{
    public function execute(DeleteAnUserRequest $request) : DeleteAnUserResponse
    {
        try {
            $anUser = $this->userRepository->findOne($request->id());
            $numUsersAffected = $this->userRepository->delete($anUser);

            return new DeleteAnUserResponse($numUsersAffected);
        } catch (\Throwable $t) {
            throw new DeleteAnUserException($t->getMessage());
        }
    }
}