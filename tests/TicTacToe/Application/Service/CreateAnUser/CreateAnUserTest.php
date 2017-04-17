<?php

declare(strict_types=1);

namespace Tests\TicTacToe\Application\Service\CreateAnUser;

use PHPUnit\Framework\TestCase;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserException;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserRequest;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserService;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use TicTacToe\Domain\Model\User;

class CreateAnUserServiceTest extends TestCase
{
    /** @var CreateAnUserService */
    private $appService;

    protected function setUp()
    {
        $this->appService = new CreateAnUserService(new InMemoryUserRepository());
    }

    public function testItShouldReturnAnUserWhenCreateAnUser()
    {
        $request = new CreateAnUserRequest('pgrau');
        $response = $this->appService->execute($request);

        $this->assertInstanceOf(User::class, $response->user());
    }

    public function testItShouldReturnAnExceptionWhenUsernameLengthIsLessThanFour()
    {
        $this->expectException(CreateAnUserException::class);

        $request = new CreateAnUserRequest('pau');
        $this->appService->execute($request);
    }

    public function testItShouldReturnAnExceptionWhenUsernameExist()
    {
        $this->expectException(CreateAnUserException::class);

        $request = new CreateAnUserRequest('lola');
        $this->appService->execute($request);

        $request = new CreateAnUserRequest('lola');
        $this->appService->execute($request);
    }
}
