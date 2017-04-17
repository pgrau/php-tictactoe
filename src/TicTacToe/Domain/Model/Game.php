<?php

declare(strict_types=1);

namespace TicTacToe\Domain\Model;

final class Game
{
    /** @var \DateTime */
    private $start;

    /** @var \DateTime */
    private $finish;

    /** @var array */
    private $board = [];

    private $icons = [1 => ' X ', 2 => ' O '];

    /** @var User */
    private $playerOne;

    /** @var User */
    private $playerTwo;

    /** @var bool */
    private $multiplayer;

    /** @var User */
    private $currentPlayer;

    /** @var User */
    private $winner;

    public function __construct(Size $size, User $userOne, User $userTwo = null)
    {
        $this->doBoardFromSize($size);

        $this->start         = new \DateTimeImmutable();
        $this->playerOne     = $userOne;
        $this->playerTwo     = $userTwo ?: User::create(UserId::create(), 'computer');
        $this->multiplayer   = $userTwo ? true : false;
        $this->currentPlayer = $userOne;
    }

    public function board(): array
    {
        return $this->board;
    }

    public function icons(): array
    {
        return $this->icons;
    }

    public function playerOne() : User
    {
        return $this->playerOne;
    }

    public function playerTwo() : User
    {
        return $this->playerTwo;
    }

    public function doBoardFromSize(Size $size) : void
    {
        for ($x = 0; $x < $size->width(); $x++) {
            for ($y = 0; $y < $size->height(); $y++) {
                $this->board[$x][$y] = null;
            }
        }
    }

    public function isFinished() : bool
    {
        return $this->finish instanceof \DateTimeInterface;
    }

    public function isMultiPlayer() : bool
    {
        return $this->multiplayer;
    }

    public function currentPlayer() : User
    {
        return $this->currentPlayer;
    }

    public function winner() : ?User
    {
        return $this->winner;
    }

    public function customizeIcons($iconPlayerOne, $iconPlayerTwo) : self
    {
        $this->icons = [1 => $iconPlayerOne, 2 => $iconPlayerTwo];

        return $this;
    }

    public function move(int $x, int $y) : void
    {
        $this->validateMovement($x, $y);

        switch($this->currentPlayer()->id()) {
            case $this->playerOne()->id():
                $this->board[$x][$y] = $this->icons()[1];
                $this->indicateIfGameIsFinished();
                $this->currentPlayer = $this->playerTwo();
                if (! $this->isMultiPlayer() && ! $this->boardIsFull()) {
                    $this->autoMove();
                }

                break;
            case $this->playerTwo()->id():
                $this->board[$x][$y] = $this->icons()[2];
                $this->indicateIfGameIsFinished();
                $this->currentPlayer = $this->playerOne();
                break;
        }
    }

    private function indicateIfGameIsFinished() : void
    {
        if ($this->isThreeInRow()) {
            $this->winner = $this->currentPlayer;
            $this->finish = new \DateTimeImmutable();
        }

        if (! $this->finish && $this->boardIsFull()) {
            $this->finish = new \DateTimeImmutable();
        }
    }

    private function isThreeInRow() : bool
    {
        $hasThree = false;
        foreach ($this->board as $keyX => $x) {
            foreach ($this->board[$keyX] as $keyY => $y) {
                if ($this->board[$keyX][$keyY]) {
                    $value = $this->board[$keyX][$keyY];
                    if ($this->isVerticalThreeInRow($value, $keyX, $keyY) ||
                        $this->isHorizontalThreeInRow($value, $keyX, $keyY) ||
                        $this->isDiagonalRightThreeInRow($value, $keyX, $keyY) ||
                        $this->isDiagonalLeftThreeInRow($value, $keyX, $keyY)
                    ) {
                        $hasThree = true;
                        break;
                    }
                }
            }
        }

        return $hasThree;
    }

    private function isVerticalThreeInRow(string $value, int $x, int $y) : bool
    {
        $hasThree = true;
        for ($i = 1; $i <= 2; $i++) {
            $next = $this->board[$x + $i][$y] ?? false;
            if ($value !== $next) {
                $hasThree = false;
                break;
            }
        }

        return $hasThree;
    }

    private function isDiagonalRightThreeInRow(string $value, int $x, int $y) : bool
    {
        $hasThree = true;
        for ($i = 1; $i <= 2; $i++) {
            $next = $this->board[$x + $i][$y + $i] ?? false;
            if ($value !== $next) {
                $hasThree = false;
                break;
            }
        }

        return $hasThree;
    }

    private function isDiagonalLeftThreeInRow(string $value, int $x, int $y) : bool
    {
        $hasThree = true;
        for ($i = 1; $i <= 2; $i++) {
            $next = $this->board[$x + $i][$y - $i] ?? false;
            if ($value !== $next) {
                $hasThree = false;
                break;
            }
        }

        return $hasThree;
    }

    private function isHorizontalThreeInRow(string $value, int $x, int $y) : bool
    {
        $hasThree = true;
        for ($i = 1; $i <= 2; $i++) {
            $next = $this->board[$x][$y + $i] ?? false;
            if ($value !== $next) {
                $hasThree = false;
                break;
            }
        }

        return $hasThree;
    }

    private function boardIsFull() : bool
    {
        $isFull = true;
        foreach ($this->board as $keyX => $x) {
            foreach ($this->board[$keyX] as $keyY => $y) {
                if (! $this->board[$keyX][$keyY]) {
                    $isFull = false;
                    break;
                }
            }
        }

        return $isFull;
    }

    private function autoMove() : void
    {
        if (! $this->isFinished()) {
            $maxX = count($this->board) - 1;
            $maxY = count($this->board[0]) - 1;
            $cellIsEmpty = true;
            while ($cellIsEmpty) {
                $x = rand(0, $maxX);
                $y = rand(0, $maxY);
                if (! $this->board[$x][$y]) {
                    $cellIsEmpty = false;
                    $this->move($x, $y);
                }
            }
        }
    }

    private function validateMovement(int $x, int $y) : void
    {
        if (!array_key_exists($x, $this->board()) || !array_key_exists($y, $this->board()[$x])) {
            $message = 'Not exist position %d %d';
            throw new GameException(sprintf($message, $x, $y));
        }

        if ($this->board()[$x][$y]) {
            $message = 'Position %d %d is busy! Please, choice other position';
            throw new GameException(sprintf($message, $x, $y));
        }
    }
}