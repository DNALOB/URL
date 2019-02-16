<?php
declare(strict_types=1);

namespace Shlinkio\Shlink\CLI\Command\Tag;

use Shlinkio\Shlink\Core\Service\Tag\TagServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateTagCommand extends Command
{
    public const NAME = 'tag:create';

    /** @var TagServiceInterface */
    private $tagService;

    public function __construct(TagServiceInterface $tagService)
    {
        parent::__construct();
        $this->tagService = $tagService;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Creates one or more tags.')
            ->addOption(
                'name',
                't',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The name of the tags to create'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);
        $tagNames = $input->getOption('name');

        if (empty($tagNames)) {
            $io->warning('You have to provide at least one tag name');
            return 1;
        }

        $this->tagService->createTags($tagNames);
        $io->success('Tags properly created');
        return 0;
    }
}
