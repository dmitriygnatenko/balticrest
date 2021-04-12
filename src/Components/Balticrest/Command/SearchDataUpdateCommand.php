<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Command;

use App\Components\Balticrest\Service\Search\SearchDataUpdaterInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchDataUpdateCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'balticrest:update-search';

    private LoggerInterface $logger;

    private SearchDataUpdaterInterface $searchDataUpdater;

    /**
     * @param string|null $name
     * @param LoggerInterface $logger
     * @param SearchDataUpdaterInterface $searchDataUpdater
     */
    public function __construct(
        string $name = null,
        LoggerInterface $logger,
        SearchDataUpdaterInterface $searchDataUpdater
    ) {
        parent::__construct($name);
        $this->logger = $logger;
        $this->searchDataUpdater = $searchDataUpdater;
    }

    protected function configure()
    {
        $this->setDescription('Search data update command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->searchDataUpdater->update();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}