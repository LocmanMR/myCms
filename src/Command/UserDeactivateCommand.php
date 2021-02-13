<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserDeactivateCommand extends Command
{
    protected static $defaultName = 'app:user:deactivate';
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    // TODO: need create AbstractBaseCommand
    public function __construct(UserRepository $userRepository, string $name = null)
    {
        parent::__construct($name);
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Deactivate user by id')
            ->addArgument('user_id', InputArgument::REQUIRED, 'User entity id')
            ->addOption(
                'reverse',
                'r',
                InputOption::VALUE_NONE,
                'Activate user'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userId = $input->getArgument('user_id');
        $reverse = $input->getOption('reverse');

        $this->userRepository->updateUserIsActiveProperty((int)$userId, (bool)$reverse);

        $io->success('User updated');

        return Command::SUCCESS;
    }
}
