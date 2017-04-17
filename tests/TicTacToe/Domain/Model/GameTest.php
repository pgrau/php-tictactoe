<?php

declare(strict_types=1);

namespace Tests\TicTacToe\Application\Service\DeleteAnUser;

use PHPUnit\Framework\TestCase;
use TicTacToe\Domain\Model\Game;
use TicTacToe\Domain\Model\GameException;
use TicTacToe\Domain\Model\Size;
use TicTacToe\Domain\Model\User;
use TicTacToe\Domain\Model\UserRepository;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;

class GameTest extends TestCase
{
    /** @var UserRepository*/
    private $userRepository;

    /** @var User */
    private $playerOne;

    /** @var User */
    private $playerTwo;

    protected function setUp()
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->playerOne = $this->userRepository->findByUsername('pepe');
        $this->playerTwo = $this->userRepository->findByUsername('juan');
    }

    public function testItShouldReturnABoardWhenStartGame()
    {
        $game = new Game(new Size(3, 3), $this->playerOne);

        $this->assertSame(3, count($game->board()));
        $this->assertSame(3, count($game->board()[0]));
    }

    public function testItShouldReturnPlayerTwoAsComputerWhenPlayOnlyOnePlayer()
    {
        $game = new Game(new Size(3, 3), $this->playerOne);

        $this->assertSame('pepe', $game->currentPlayer()->username());
        $this->assertSame('computer', $game->playerTwo()->username());
        $this->assertFalse($game->isMultiPlayer());
    }

    public function testItShouldReturnMultiplayerWhenPlayTwoPlayers()
    {
        $game = new Game(new Size(3, 3), $this->playerOne, $this->playerTwo);

        $this->assertTrue($game->isMultiPlayer());
    }

    public function testItShouldReturnCustomIconsWhenIconsAreCustomized()
    {
        $game = new Game(new Size(3, 3), $this->playerOne, $this->playerTwo);
        $game->customizeIcons('Q', 'A');

        $this->assertSame('Q', $game->icons()[1]);
        $this->assertSame('A', $game->icons()[2]);
    }

    /**
     * @dataProvider wrongPositionsProvider
     */
    public function testItShouldReturnAnExceptionWhenPlayerOneMoveWithWrongPosition($x, $y)
    {
        $this->expectException(GameException::class);

        $game = new Game(new Size(3, 3), $this->playerOne, $this->playerTwo);
        $game->move($x, $y);
    }

    public function testItShouldReturnAnExceptionWhenPlayerMoveToBusyPosition()
    {
        $this->expectException(GameException::class);

        $game = new Game(new Size(3, 3), $this->playerOne, $this->playerTwo);
        $game->move(1, 1);
        $game->move(1, 1);
    }

    public function testItShouldReturnCurrentPlayerAsPlayerOneWhenPlayOnePlayer()
    {
        $game = new Game(new Size(3, 3), $this->playerOne);
        $game->move(1, 1);

        $this->assertSame('pepe', $game->playerOne()->username());
    }

    public function testItShouldReturnGameIsFinishedWhenAreThreeInRow()
    {
        $game = new Game(new Size(3, 3), $this->playerOne, $this->playerTwo);

        $game->move(0, 0);
        $game->move(0, 1);
        $game->move(1, 0);
        $game->move(1, 1);
        $game->move(2, 0);

        $this->assertTrue($game->isFinished());
        $this->assertSame('pepe', $game->winner()->username());
    }

    public function testItShouldReturnNobodyAsWinnerWhenBoardIsFull()
    {
        $game = new Game(new Size(3, 3), $this->playerOne, $this->playerTwo);

        $game->move(0, 0);
        $game->move(0, 1);

        $game->move(1, 0);
        $game->move(1, 1);

        $game->move(2, 1);
        $game->move(2, 0);

        $game->move(0, 2);
        $game->move(1, 2);

        $game->move(2, 2);

        $this->assertTrue($game->isFinished());
        $this->assertNull($game->winner());
    }

    public function wrongPositionsProvider()
    {
        return [
            [5, 2],
            [1, 4],
        ];
    }
}