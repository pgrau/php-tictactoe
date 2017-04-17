<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use TicTacToe\Application\Service\DeleteAnUser\DeleteAnUserRequest;
use TicTacToe\Application\Service\DeleteAnUser\DeleteAnUserService;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;

class DeleteUserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('tictactoe:user:delete')
            ->setDescription('Delete an user')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userRepository = new InMemoryUserRepository();
        $appService = new DeleteAnUserService($userRepository);

        $this->drawUsers($output, $userRepository);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter id: ');
        $question->setValidator(function ($answer) use ($appService) {
            $response = $appService->execute(new DeleteAnUserRequest($answer));

            return 'Users deleted: ' . $response->numUsersAffected();
        });

        $response = $helper->ask($input, $output, $question);
        $output->writeln('<error>'.$response.'</error>');
    }

    /**
     * @param OutputInterface $output
     * @param $userRepository
     */
    protected function drawUsers(OutputInterface $output, $userRepository)
    {
        $users = [];
        foreach ($userRepository->findAll() as $anUser) {
            $users[] = [$anUser->id(), $anUser->username()];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['id', 'username'])
            ->setRows($users);
        $table->render();
    }
}
