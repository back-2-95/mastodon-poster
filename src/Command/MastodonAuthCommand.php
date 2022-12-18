<?php

namespace App\Command;

use App\Service\MastodonService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'mastodon:auth',
    description: 'Add a short description for your command',
)]
class MastodonAuthCommand extends Command
{
    private MastodonService $mastodonService;

    public function __construct(MastodonService $mastodonService)
    {
        $this->mastodonService = $mastodonService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $token = $this->mastodonService->getToken();

        if ($token) {
            $io->success('Token: ' . $token);
            return Command::SUCCESS;
        }
        else {
            $output->writeln('Could not retrieve token');
            return Command::FAILURE;
        }
    }
}
