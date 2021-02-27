<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Statistics;

use Google_Service_Analytics;
use Google_Client;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/*
Путь к серверному ключу Google Analytics.
- Выбираем существующий или создаем новый проект в Google Developers Console (https://console.cloud.google.com/api).
- На странице "Обзор" переходим по ссылке Analytics API (находится в блоке "Другие популярные API").
- Нажимаем кнопку "Включить".
- В левом меню нажимаем на ссылку "Учетные данные".
- Нажимаем кнопку "Создать учетные данные" и далее "Ключ сервисного аккаунта".
- Выбираем проект, указываем название аккаунта (любое), тип ключа выбираем JSON. Нажимаем "Создать", получаем ключ.
- На странице "Учетные данные" переходим по ссылке "Управление сервисными аккаунтами".
  В списке должен присутствовать аккаунт, который Вы только что создали.

После получения ключа и адреса важно включить доступ к требуемому представлению Google Analytics по API. Для этого:
- На странице Google Analytics в меню "Администратор" выбираем нужный аккаунт, ресурс и представление.
- В блоке "Представления" выбираем пункт "Управление пользователями".
- В поле "Предоставить разрешения" указываем email, и нажимаем кнопку "Добавить".

Идентификатор представления увидеть можно, зайдя в Администратор-Аккаунт-Ресурс-Представление-Настройки представления.
*/
class GoogleAnalyticsStatistics implements GoogleAnalyticsStatisticsInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var Google_Service_Analytics|null */
    private $analytics;

    /** @var string|null */
    private $viewId;

    /** @var TagAwareCacheInterface */
    private $cache;

    /**
     * @param LoggerInterface $logger
     * @param Google_Client $client
     * @param ContainerBagInterface $containerBag
     * @param TagAwareCacheInterface $cache
     */
    public function __construct(
        LoggerInterface $logger,
        Google_Client $client,
        ContainerBagInterface $containerBag,
        TagAwareCacheInterface $cache
    ) {
        $this->logger = $logger;
        $this->cache = $cache;

        if ($client->getClientId() !== '') {
            $client->setScopes([Google_Service_Analytics::ANALYTICS_READONLY]);
            $this->analytics = new Google_Service_Analytics($client);
            $this->viewId = $containerBag->get('app.ga_view_id');
        }
    }

    /**
     * @return array
     */
    public function getCachedStatistics(): array
    {
        try {
            return $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
                $item->expiresAfter(self::CACHE_EXPIRE_TIME);

                return $this->getStatistics();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getStatistics();
        }
    }

    /**
     * @return array
     */
    public function getStatistics(): array
    {
        $statistics = [];

        if ($this->analytics === null) {
            return [];
        }

        // Получаем данные за 30 дней, разделенные по дням
        $rows = $this->get30daysStatistics();

        foreach ($rows as $row) {
            $label = $row[2] . '.' . $row[1];

            $statistics[self::DAY][self::FIELD_USERS][self::LABELS][] = $label;
            $statistics[self::DAY][self::FIELD_USERS][self::VALUES][] = (int) $row[3];
            $statistics[self::DAY][self::FIELD_NEW_USERS][self::LABELS][] = $label;
            $statistics[self::DAY][self::FIELD_NEW_USERS][self::VALUES][] = (int) $row[4];
            $statistics[self::DAY][self::FIELD_HITS][self::LABELS][] = $label;
            $statistics[self::DAY][self::FIELD_HITS][self::VALUES][] = (int) $row[5];
            $statistics[self::DAY][self::FIELD_SESSIONS][self::LABELS][] = $label;
            $statistics[self::DAY][self::FIELD_SESSIONS][self::VALUES][] = (int) $row[6];
            $statistics[self::DAY][self::FIELD_AVG_SESSION_DURATION][self::LABELS][] = $label;
            $statistics[self::DAY][self::FIELD_AVG_SESSION_DURATION][self::VALUES][] = (int) $row[7];
            $statistics[self::DAY][self::FIELD_BOUNCE_RATE][self::LABELS][] = $label;
            $statistics[self::DAY][self::FIELD_BOUNCE_RATE][self::VALUES][] = (int) $row[8];
        }

        // Получаем данные за 3 месяца, разделенные по неделям
        $rows = $this->get3monthsStatistics();

        foreach ($rows as $row) {
            $ts = strtotime($row[0] . 'W' . $row[1] . '1');
            $label = date('d.m', $ts) . '-' .  date('d.m', strtotime('+6 days', $ts));

            $statistics[self::WEEK][self::FIELD_USERS][self::LABELS][] = $label;
            $statistics[self::WEEK][self::FIELD_USERS][self::VALUES][] = (int) $row[2];
            $statistics[self::WEEK][self::FIELD_NEW_USERS][self::LABELS][] = $label;
            $statistics[self::WEEK][self::FIELD_NEW_USERS][self::VALUES][] = (int) $row[3];
            $statistics[self::WEEK][self::FIELD_HITS][self::LABELS][] = $label;
            $statistics[self::WEEK][self::FIELD_HITS][self::VALUES][] = (int) $row[4];
            $statistics[self::WEEK][self::FIELD_SESSIONS][self::LABELS][] = $label;
            $statistics[self::WEEK][self::FIELD_SESSIONS][self::VALUES][] = (int) $row[5];
            $statistics[self::WEEK][self::FIELD_AVG_SESSION_DURATION][self::LABELS][] = $label;
            $statistics[self::WEEK][self::FIELD_AVG_SESSION_DURATION][self::VALUES][] = (int) $row[6];
            $statistics[self::WEEK][self::FIELD_BOUNCE_RATE][self::LABELS][] = $label;
            $statistics[self::WEEK][self::FIELD_BOUNCE_RATE][self::VALUES][] = (int) $row[7];
        }

        return $statistics;
    }

    /**
     * Получаем данные за 30 дней, разделенные по дням
     *
     * @return array
     */
    private function get30daysStatistics(): array
    {
        if ($this->analytics === null) {
            return [];
        }

        $results = $this->analytics->data_ga->get(
            'ga:' . $this->viewId,
            '30daysAgo',
            'today',
            'ga:' . self::FIELD_USERS
            . ',ga:' . self::FIELD_NEW_USERS
            . ',ga:' . self::FIELD_HITS
            . ',ga:' . self::FIELD_SESSIONS
            . ',ga:' . self::FIELD_AVG_SESSION_DURATION
            . ',ga:' . self::FIELD_BOUNCE_RATE,
            ['dimensions' => 'ga:year,ga:month,ga:day']
        );

        $rows = $results->getRows();

        return is_array($rows) ? $rows : [];
    }

    /**
     * Получаем данные за 3 месяца, разделенные по неделям
     *
     * @return array
     */
    private function get3monthsStatistics(): array
    {
        if ($this->analytics === null) {
            return [];
        }

        $results = $this->analytics->data_ga->get(
            'ga:' . $this->viewId,
            '90daysAgo',
            'today',
            'ga:' . self::FIELD_USERS
            . ',ga:' . self::FIELD_NEW_USERS
            . ',ga:' . self::FIELD_HITS
            . ',ga:' . self::FIELD_SESSIONS
            . ',ga:' . self::FIELD_AVG_SESSION_DURATION
            . ',ga:' . self::FIELD_BOUNCE_RATE,
            ['dimensions' => 'ga:year,ga:week']
        );

        $rows = $results->getRows();

        return is_array($rows) ? $rows : [];
    }
}