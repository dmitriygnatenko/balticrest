<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Schedule;

interface ScheduleUpdaterInterface
{
    /** @var string */
    public const YANDEX_SCHEDULE_URL = 'https://api.rasp.yandex.net/v3.0/search/';

    /** @var string */
    public const TYPE_SUBURBAN = 'suburban';

    public function update(): void;
}