<?php

declare(strict_types=1);

namespace TicTacToe\Domain\Model;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;

class UserId
{
    /** @var string */
    private $id;

    private function __construct(string $id)
    {
        Assertion::uuid($id, sprintf('%s must be a valid uuid', $id));

        $this->id = $id;
    }

    public static function create() : self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function __toString()
    {
        return $this->id;
    }
}
