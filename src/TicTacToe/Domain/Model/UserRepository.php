<?php

declare(strict_types=1);

namespace TicTacToe\Domain\Model;

interface UserRepository
{
    public function find(string $id);

    public function findOne(string $id) : User;

    public function findByUsername(string $username);

    public function persist(User $user) : User;

    public function delete(User $user) : int;
}