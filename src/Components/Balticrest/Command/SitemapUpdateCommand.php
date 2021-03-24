<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Command;

use App\Components\Balticrest\Service\Sitemap\SitemapGeneratorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class SitemapUpdateCommand extends Command
{
    /** @var string */
    private const SITEMAP_PATH = '/public/__sitemap.xml';

    /** @var string */
    protected static $defaultName = 'balticrest:update-sitemap';

    private LoggerInterface $logger;

    private ContainerBagInterface $containerBag;

    private SitemapGeneratorInterface $sitemapGenerator;

    /**
     * @param string|null $name
     * @param LoggerInterface $logger
     * @param ContainerBagInterface $containerBag
     * @param SitemapGeneratorInterface $sitemapGenerator
     */
    public function __construct(
        string $name = null,
        LoggerInterface $logger,
        ContainerBagInterface $containerBag,
        SitemapGeneratorInterface $sitemapGenerator
    ) {
        parent::__construct($name);
        $this->sitemapGenerator = $sitemapGenerator;
        $this->containerBag = $containerBag;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this->setDescription('Sitemap.xml update command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sitemapFullPath = $this->containerBag->get('kernel.project_dir') . self::SITEMAP_PATH;

        $result = file_put_contents($sitemapFullPath, $this->sitemapGenerator->generateContent());

        if ($result === false) {
            $this->logger->error('Error while updating sitemap.xml');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
