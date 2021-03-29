<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Command;

use App\Components\Balticrest\Service\Schedule\ScheduleUpdaterInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleUpdateCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'balticrest:update-schedule';

    private LoggerInterface $logger;

    private ScheduleUpdaterInterface $scheduleUpdater;

    /**
     * @param string|null $name
     * @param LoggerInterface $logger
     * @param ScheduleUpdaterInterface $scheduleUpdater
     */
    public function __construct(
        string $name = null,
        LoggerInterface $logger,
        ScheduleUpdaterInterface $scheduleUpdater
    ) {
        parent::__construct($name);
        $this->logger = $logger;
        $this->scheduleUpdater = $scheduleUpdater;
    }

    protected function configure()
    {
        $this->setDescription('Yandex schedule update command');
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
            $this->scheduleUpdater->update();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}