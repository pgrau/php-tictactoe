<?php

declare(strict_types=1);

namespace TicTacToe\Domain\Model;

final class Size
{
    /** @var int */
    private $width;

    /** @var int */
    private $height;

    public function __construct(int $width, int $height)
    {
        \Assert\Assertion::range($width, 3, 20);
        \Assert\Assertion::range($height, 3, 20);

        $this->width = $width;
        $this->height = $height;
    }

    public function height() : int
    {
        return $this->height;
    }

    public function width() : int
    {
        return $this->width;
    }
}