<?php

declare(strict_types=1);

namespace TicTacToe\Application\Service\DeleteAnUser;

class DeleteAnUserRequest
{
    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id() : string
    {
        return $this->id;
    }
}