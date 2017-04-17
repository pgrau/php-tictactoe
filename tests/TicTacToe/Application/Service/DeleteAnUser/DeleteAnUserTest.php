<?php

declare(strict_types=1);

namespace Tests\TicTacToe\Application\Service\DeleteAnUser;

use PHPUnit\Framework\TestCase;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserRequest;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserService;
use TicTacToe\Application\Service\DeleteAnUser\DeleteAnUserException;
use TicTacToe\Application\Service\DeleteAnUser\DeleteAnUserRequest;
use TicTacToe\Application\Service\DeleteAnUser\DeleteAnUserService;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use TicTacToe\Domain\Model\User;

class DeleteAnUserServiceTest extends TestCase
{
    /** @var DeleteAnUserService */
    private $appService;

    /** @var User */
    private $anUser;

    protected function setUp()
    {
        $userRepository = new InMemoryUserRepository();

        $appService = new CreateAnUserService($userRepository);
        $this->anUser = ($appService->execute(new CreateAnUserRequest('pgrau')))->user();

        $this->appService = new DeleteAnUserService($userRepository);
    }

    public function testItShouldReturnOneWhenDeleteAnUser()
    {
        $request = new DeleteAnUserRequest((string) $this->anUser->id());
        $response = $this->appService->execute($request);

        $this->assertSame(1, $response->numUsersAffected());
    }

    public function testItShouldReturnAnExceptionWhenDeleteAnUserAndUserNotExist()
    {
        $this->expectException(DeleteAnUserException::class);

        $request = new DeleteAnUserRequest('fake');
        $this->appService->execute($request);
    }
}
