<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Schedule;

interface ScheduleUpdaterInterface
{
    public function update(): void;
}