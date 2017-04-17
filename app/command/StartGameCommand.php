<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use TicTacToe\Application\Service\GetAnUser\GetAnUserRequest;
use TicTacToe\Application\Service\GetAnUser\GetAnUserService;
use TicTacToe\Domain\Model\Game;
use TicTacToe\Domain\Model\Size;
use TicTacToe\Domain\Model\UserRepository;
use TicTacToe\Infrastructure\Persistence\InMemory\InMemoryUserRepository;

class StartGameCommand extends Command
{
    /** @var UserRepository */
    private $userRepository;

    /** @var GetAnUserService */
    private $getUserService;

    protected function configure()
    {
        $this
            ->setName('tictactoe:game:start')
            ->setDescription('Start game')
            ->addArgument('color1', InputArgument::OPTIONAL, 'Color of player 1', 'magenta')
            ->addArgument('color2', InputArgument::OPTIONAL, 'Color of player 2', 'cian')
            ->addArgument('icon1', InputArgument::OPTIONAL, 'Icon of player 1', ' X ')
            ->addArgument('icon2', InputArgument::OPTIONAL, 'Icon of player 2', ' O ')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->getUserService = new GetAnUserService($this->userRepository);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $colorPlayerOne = $input->getArgument('color1');
        $colorPlayerTwo = $input->getArgument('color2');
        $iconPlayerOne = $input->getArgument('icon1');
        $iconPlayerTwo = $input->getArgument('icon2');

        $helper = $this->getHelper('question');
        $numOfPlayers = $this->askNumOfPlayers($input, $output, $helper);
        $players = $this->askUsernamePlayerAndGetPlayers($input, $output, $helper, $numOfPlayers);

        $game = new Game(new Size(3, 3), $players[0], $players[1] ?? null);
        $game->customizeIcons(
            "<fg={$colorPlayerOne};> {$iconPlayerOne} </>",
            "<fg={$colorPlayerTwo};> {$iconPlayerTwo}  </>"
        );
        while (! $game->isFinished()) {
            $this->renderTable($output, $game);

            if ($game->isMultiPlayer() || $game->currentPlayer()->id() === $players[0]->id()) {
                $message = 'Please %s enter new position: ';
                $question = new Question(sprintf($message, $game->currentPlayer()->username()));
                $question->setValidator(function ($answer) use ($game) {
                    if (!preg_match('/^(\d)+([,|\ ])+(\d)$/', $answer, $matches)) {
                        throw new \InvalidArgumentException('Invalid format position');
                    }

                    $game->move($matches[1], $matches[3]);

                    return $answer;
                });

                $helper->ask($input, $output, $question);
            }
        }

        $this->renderTable($output, $game);
        $output->writeln('<fg=green>Game finished!!!</>');
        $message = $game->winner() ? 'Congrats '.$game->winner()->username().'!!!' : 'Nobody win';

        $output->writeln('<fg=green>'.$message.'</>');
    }

    private function doRowsByGame(Game $game)
    {
        $rows = [];
        foreach ($game->board() as $keyX => $row) {
            $data = [];
            foreach ($row as $keyY => $item) {
                $data[$keyY] = $item ?? $keyX . ',' . $keyY;
            }
            $rows[] = $data;
            $rows[] = new TableSeparator();
        }

        unset($rows[count($rows) - 1]);

        return $rows;
    }

    private function askNumOfPlayers(InputInterface $input, OutputInterface $output, QuestionHelper $helper)
    {
        $question = new ChoiceQuestion(
            'Please select number of players',
            array(1, 2),
            0
        );
        $question->setErrorMessage('Number %s is invalid.');

        return $helper->ask($input, $output, $question);
    }

    private function askUsernamePlayerAndGetPlayers(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $helper,
        int $numOfPlayers
    ) {
        $players = [];
        for ($i = 1; $i < $numOfPlayers + 1; $i++) {
            $question = new Question('Please enter the username of player ' . $i . ': ');
            $question->setValidator(function ($answer) {
                $response = $this->getUserService->execute(new GetAnUserRequest($answer));

                return $response->user();
            });

            $players[] = $helper->ask($input, $output, $question);
        }

        return $players;
    }

    protected function renderTable(OutputInterface $output, Game $game)
    {
        $table = new Table($output);
        $table
            ->setRows($this->doRowsByGame($game));
        $table->render();
    }
}
