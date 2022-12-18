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
    name: 'mastodon:post',
    description: 'Create a status post to your Mastodon account',
)]
class MastodonPostCommand extends Command
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
            ->addArgument('status', InputArgument::REQUIRED, 'Status body as text')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $status = $input->getArgument('status');

        $status = $this->mastodonService->postStatus($status);

        $io->success('Status created: ' . $status['url']);

        return Command::SUCCESS;
    }
}
