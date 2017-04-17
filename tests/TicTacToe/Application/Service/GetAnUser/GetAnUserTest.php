<?php

declare(strict_types=1);

namespace Tests\TicTacToe\Application\Service\DeleteAnUser;

use PHPUnit\Framework\TestCase;
use TicTacToe\Application\Service\GetAnUser\GetAnUserException;
use TicTacToe\Application\Service\GetAnUser\GetAnUserRequest;
use TicTacToe\Application\Service\GetAnUser\GetAnUserService;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use TicTacToe\Domain\Model\User;

class GetAnUserServiceTest extends TestCase
{
    /** @var GetAnUserService */
    private $appService;

    protected function setUp()
    {
        $userRepository = new InMemoryUserRepository();
        $this->appService = new GetAnUserService($userRepository);
    }

    public function testItShouldReturnOneUserWhenFindAnUser()
    {
        $request = new GetAnUserRequest('pepe');
        $response = $this->appService->execute($request);

        $this->assertInstanceOf(User::class, $response->user());
    }

    public function testItShouldReturnAnExceptionWhenNotFoundAnUser()
    {
        $this->expectException(GetAnUserException::class);

        $request = new GetAnUserRequest('fake');
        $this->appService->execute($request);
    }
}
