<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Statistics;

interface GoogleAnalyticsStatisticsInterface
{
    /** @var */
    public const CACHE_KEY = 'google_analytics_cache';

    /** @var int */
    public const CACHE_EXPIRE_TIME = 86400;

    /** @var string */
    public const DAY = 'day';

    /** @var string */
    public const WEEK = 'week';

    /** @var string */
    public const LABELS = 'labels';

    /** @var string */
    public const VALUES = 'values';

    /** @var string Количество уникальных пользователей */
    public const FIELD_USERS = 'users';

    /** @var string Количество новых пользователей */
    public const FIELD_NEW_USERS = 'newUsers';

    /** @var string Количество просмотренных страниц */
    public const FIELD_HITS = 'hits';

    /** @var string Количество сессий */
    public const FIELD_SESSIONS = 'sessions';

    /** @var string Средняя длительность сессии в минутах */
    public const FIELD_AVG_SESSION_DURATION = 'avgSessionDuration';

    /** @var string Показатель отказов в % */
    public const FIELD_BOUNCE_RATE = 'bounceRate';

    /**
     * @return array
     */
    public function getCachedStatistics(): array;

    /**
     * @return array
     */
    public function getStatistics(): array;
}