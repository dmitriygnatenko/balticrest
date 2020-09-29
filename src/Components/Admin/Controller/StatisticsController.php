<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\Statistics\GoogleAnalyticsStatistics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /** @var GoogleAnalyticsStatistics */
    public $googleAnalyticsStatistics;

    /**
     * @param GoogleAnalyticsStatistics $googleAnalyticsStatistics
     */
    public function __construct(GoogleAnalyticsStatistics $googleAnalyticsStatistics)
    {
        $this->googleAnalyticsStatistics = $googleAnalyticsStatistics;
    }

    /**
     * @Route("/", name="admin.statistics_index", methods={"GET"})
     *
     * @return Response
     */
    public function list(): Response
    {
        $statistics = $this->googleAnalyticsStatistics->getStatistics();

        return $this->render('admin/statistics/index.html.twig', [
            'statistics' => $statistics
        ]);
    }
}