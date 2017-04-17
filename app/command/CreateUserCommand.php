<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserRequest;
use TicTacToe\Application\Service\CreateAnUser\CreateAnUserService;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;

class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('tictactoe:user:create')
            ->setDescription('Create an user')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userRepository = new InMemoryUserRepository();
        $appService = new CreateAnUserService($userRepository);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter your username: ');
        $question->setValidator(function ($answer) use ($appService) {
            $response = $appService->execute(new CreateAnUserRequest($answer));

            return 'User created with id: ' . $response->user()->id();
        });

        $helper->ask($input, $output, $question);
    }
}
