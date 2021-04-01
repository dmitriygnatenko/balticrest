<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Schedule;

interface StationsInterface
{
    /** @var string Ж/д станция Зеленоградск */
    public const ZELENOGRADSK_RAILROAD_STATION = 's9611997';

    /** @var string Ж/д станция Светлогорск-1 */
    public const SVETLOGORSK_1_RAILROAD_STATION = 's9612012';

    /** @var string Ж/д станция Светлогорск-2 */
    public const SVETLOGORSK_2_RAILROAD_STATION = 's9612059';

    /** @var string Ж/д станция Калининград-Южный */
    public const KALININGRAD_Y_RAILROAD_STATION = 's9623137';

    /** @var string Ж/д станция Калининград-Северный */
    public const KALININGRAD_S_RAILROAD_STATION = 's9623138';

    /** @var string URL Ж/д станции Светлогорск-1  */
    public const SVETLOGORSK_1_RAILROAD_POINT_URL = 'svetlogorsk1';

    /** @var string URL Ж/д станции Светлогорск-2  */
    public const SVETLOGORSK_2_RAILROAD_POINT_URL = 'svetlogorsk2';

    /** @var string URL Ж/д станции Зеленоградск  */
    public const ZELENOGRADSK_RAILROAD_POINT_URL = 'zelenogradsk';

    /** @var string[] Маппинг наших URL и внешних идентификаторов */
    public const STATIONS_MAPPING = [
        self::ZELENOGRADSK_RAILROAD_POINT_URL => self::ZELENOGRADSK_RAILROAD_STATION,
        self::SVETLOGORSK_1_RAILROAD_POINT_URL => self::SVETLOGORSK_1_RAILROAD_STATION,
        self::SVETLOGORSK_2_RAILROAD_POINT_URL => self::SVETLOGORSK_2_RAILROAD_STATION,
    ];
}