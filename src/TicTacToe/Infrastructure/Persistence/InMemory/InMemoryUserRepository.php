<?php

declare(strict_types=1);

namespace TicTacToe\Infrastructure\Persistence\InMemory;

use Ramsey\Uuid\Uuid;
use TicTacToe\Domain\Model\User;
use TicTacToe\Domain\Model\UserId;
use TicTacToe\Domain\Model\UserRepository;
use TicTacToe\Infrastructure\Persistence\UserRepositoryAbstract;

final class InMemoryUserRepository implements UserRepository
{
    /** @var User[] */
    protected $users = [];

    public function __construct()
    {
        $this->users[] = User::create(UserId::create(Uuid::uuid4()->toString()), 'pepe');
        $this->users[] = User::create(UserId::create(Uuid::uuid4()->toString()), 'juan');
    }

    public function persist(User $user) : User
    {
        $this->validateIfUsernameExist($user->username());

        $this->users[] = $user;

        return $user;
    }

    public function delete(User $user) : int
    {
        $numUsersAffected = 0;
        foreach ($this->users as $key => $item) {
            if ((string) $item->id() === (string) $user->id()) {
                unset($this->users[$key]);
                $numUsersAffected = 1;
                break;
            }
        }

        return $numUsersAffected;
    }

    public function find(string $id) : User
    {
        $anUser = null;
        foreach ($this->users as $key => $item) {
            if ((string) $item->id() === $id) {
                $anUser = $this->users[$key];
                break;
            }
        }

        return $anUser;
    }

    public function findByUsername(string $username)
    {
        $anUser = null;
        foreach ($this->users as $key => $item) {
            if ($item->username() === $username) {
                $anUser = $this->users[$key];
                break;
            }
        }

        return $anUser;
    }

    public function findOne(string $id) : User
    {
        if (! $anUser = $this->find($id)) {
            throw new \InvalidArgumentException('User not exist');
        }

        return $anUser;
    }

    private function validateIfUsernameExist(string $username)
    {
        foreach ($this->users as $key => $item) {
            if ($item && $item->username() === $username) {
                throw new \InvalidArgumentException('Username exist');
            }
        }
    }
}