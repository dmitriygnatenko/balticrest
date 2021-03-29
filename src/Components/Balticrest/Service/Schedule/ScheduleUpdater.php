<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Schedule;

use App\Repository\ScheduleRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class ScheduleUpdater implements ScheduleUpdaterInterface, StationsInterface
{
    private array $routesToUpdate = [
        // Зеленоградск поезд
        [
            self::ZELENOGRADSK_RAILROAD_STATION,
            self::KALININGRAD_S_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::ZELENOGRADSK_RAILROAD_STATION,
            self::KALININGRAD_Y_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::KALININGRAD_S_RAILROAD_STATION,
            self::ZELENOGRADSK_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::KALININGRAD_Y_RAILROAD_STATION,
            self::ZELENOGRADSK_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        // Светлогорск 1 поезд
        [
            self::SVETLOGORSK_1_RAILROAD_STATION,
            self::KALININGRAD_S_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::SVETLOGORSK_1_RAILROAD_STATION,
            self::KALININGRAD_Y_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::KALININGRAD_S_RAILROAD_STATION,
            self::SVETLOGORSK_1_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::KALININGRAD_Y_RAILROAD_STATION,
            self::SVETLOGORSK_1_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        // Светлогорск 2 поезд
        [
            self::SVETLOGORSK_2_RAILROAD_STATION,
            self::KALININGRAD_S_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::SVETLOGORSK_2_RAILROAD_STATION,
            self::KALININGRAD_Y_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::KALININGRAD_S_RAILROAD_STATION,
            self::SVETLOGORSK_2_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
        [
            self::KALININGRAD_Y_RAILROAD_STATION,
            self::SVETLOGORSK_2_RAILROAD_STATION,
            self::TYPE_SUBURBAN
        ],
    ];

    private LoggerInterface $logger;

    private HttpClientInterface $httpClient;

    private string $apiKey;
    /**
     * @var ScheduleRepository
     */
    private ScheduleRepository $scheduleRepository;

    /**
     * @param LoggerInterface $logger
     * @param ParameterBagInterface $parameterBag
     * @param HttpClientInterface $httpClient
     * @param ScheduleRepository $scheduleRepository
     */
    public function __construct(
        LoggerInterface $logger,
        ParameterBagInterface $parameterBag,
        HttpClientInterface $httpClient,
        ScheduleRepository $scheduleRepository
    ) {
        $this->apiKey = (string) $parameterBag->get('app.yandex_schedule_api_key');
        $this->logger = $logger;
        $this->httpClient = $httpClient;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function update(): void
    {
        foreach ($this->routesToUpdate as $route) {
            try {
                $this->updateRoute($route[0], $route[1], $route[2]);
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            }
        }
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $type
     *
     * @throws Throwable
     */
    private function updateRoute(string $from, string $to, string $type): void
    {
        $response = $this->httpClient->request(
            'GET',
            self::YANDEX_SCHEDULE_URL,
            [
                'headers' => [
                    'Authorization' => $this->apiKey,
                ],
                'query' => [
                    'from' => $from,
                    'to' => $to,

                ],
            ]
        );

        $content = $response->toArray();

        if ((int) $content['pagination']['total'] === (int) $content['pagination']['limit']) {
            $this->logger->alert('Yandex schedule threads limit exceeded', [
                'content' => $content
            ]);
        }

        $data = $this->formatDataFromYandexContent($content);

        if (empty(!$data)) {
            $this->scheduleRepository->createOrUpdateSchedule($from, $to, $data);
        }
    }

    /**
     * @param array $content
     *
     * @return array
     */
    private function formatDataFromYandexContent(array $content): array
    {
        $data = [];

        $segments = $content['segments'] ?? [];

        if (empty($segments)) {
            return $data;
        }

        foreach ($segments as $segment) {
            $days = $segment['days'] ?? '';
            $number = $segment['thread']['number'] ?? '';
            $duration = $segment['duration'] ?? 0;

            $arrival = $segment['arrival'] ?? '';
            if (preg_match('/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/', $arrival, $matches)) {
                $arrival = $matches[1] . ':' . $matches[2];
            }

            $departure = $segment['departure'] ?? '';
            if (preg_match('/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/', $departure, $matches)) {
                $departure = $matches[1] . ':' . $matches[2];
            }

            $data[] = [
                'departure' => $departure,
                'arrival' => $arrival,
                'duration' => $duration,
                'days' => $days,
                'number' => $number,
            ];
        }

        return $data;
    }
}