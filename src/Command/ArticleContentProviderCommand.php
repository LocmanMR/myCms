<?php
declare(strict_types=1);


namespace App\Command;

use App\Service\Interfaces\ArticleContentProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ArticleContentProviderCommand
 * @package App\Command
 * @example php bin/console app:article:content_provider 5 --word=name --wordCount=10
 */
class ArticleContentProviderCommand extends Command
{
    protected static $defaultName = 'app:article:content_provider';

    private ArticleContentProviderInterface $articleContentProvider;

    public function __construct(ArticleContentProviderInterface $articleContentProvider, string $name = null)
    {
        $this->articleContentProvider = $articleContentProvider;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Provide content for articles')
            ->addArgument(
                'paragraphs',
                InputArgument::REQUIRED,
                'number of paragraphs in content'
            )
            ->addOption('word',
                'w',
                InputOption::VALUE_OPTIONAL,
                'the word to add to the content'
            )
            ->addOption(
                'wordCount',
                'wc',
                InputOption::VALUE_OPTIONAL,
                'word count'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $paragraphs = $input->getArgument('paragraphs');
        $word = null;
        $wordCount = 0;

        if ($input->getOption('word') && $input->getOption('wordCount')) {
            $word = $input->getOption('word');
            $wordCount = $input->getOption('wordCount');
        }

        $content = $this->articleContentProvider->get((int)$paragraphs, (string)$word, (int)$wordCount);

        $io->block($content);

        return Command::SUCCESS;
    }

}
