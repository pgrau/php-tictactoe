<?php

declare(strict_types=1);

namespace TicTacToe\Domain\Model;

interface UserRepository
{
    public function find(string $id) : ?User;

    public function findOne(string $id) : User;

    public function findByUsername(string $username) : ?User;

    public function persist(User $user) : User;

    public function delete(User $user) : int;
}