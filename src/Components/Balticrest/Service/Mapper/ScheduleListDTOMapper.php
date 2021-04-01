<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\ScheduleListDTO;
use App\Components\Balticrest\Service\DTO\ScheduleRecordDTO;
use App\Entity\Schedule;
use Symfony\Component\HttpFoundation\RequestStack;
use Transliterator;

class ScheduleListDTOMapper
{
    private ?string $locale = null;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->locale = $requestStack->getMasterRequest()->getLocale();
    }

    /**
     * @param Schedule[] $schedules
     *
     * @return ScheduleListDTO[]
     */
    public function fillAll(array $schedules): array
    {
        $records = [];

        foreach ($schedules as $schedule) {
            $records[] = $this->fill($schedule);
        }

        return $records;
    }

    /**
     * @param Schedule $schedule
     *
     * @return ScheduleListDTO
     */
    public function fill(Schedule $schedule): ScheduleListDTO
    {
        $listDTO = new ScheduleListDTO();

        $data = $schedule->getData();
        $records = $data['records'] ?? [];

        $listDTO
            ->setToCode($schedule->getStationTo())
            ->setFromCode($schedule->getStationFrom())
            ->setLastUpdate($schedule->getLastUpdate())
            ->setFromTitle($data['from_title'] ?? null)
            ->setToTitle($data['to_title'] ?? null);

        foreach ($records as $record) {
            $duration = isset($record['duration']) ? (int) $record['duration'] : null;

            $recordDTO = (new ScheduleRecordDTO())
                ->setDeparture($record['departure'] ?? null)
                ->setArrival($record['arrival'] ?? null)
                ->setNumber($record['number'] ?? null)
                ->setDuration($this->formatDuration($duration))
                ->setDays($this->formatDays($record['days'] ?? null));

            $listDTO->addRecord($recordDTO);
        }

        if ($this->locale !== 'ru') {
            if ($listDTO->getFromTitle() !== null) {
                $listDTO->setFromTitle(
                    Transliterator::create("Any-Latin; Latin-ASCII")->transliterate($listDTO->getFromTitle())
                );
            }

            if ($listDTO->getToTitle() !== null) {
                $listDTO->setToTitle(
                    Transliterator::create("Any-Latin; Latin-ASCII")->transliterate($listDTO->getToTitle())
                );
            }
        }

        return $listDTO;
    }

    /**
     * @param int|null $durationInSec
     *
     * @return string|null
     */
    private function formatDuration(?int $durationInSec): ?string
    {
        if ($durationInSec === null) {
            return null;
        }

        $durationInMin = (int) round($durationInSec / 60);

        if ($durationInMin < 60) {
            $duration = $durationInMin . ' ' . ($this->locale === 'ru' ? 'мин' : 'min');
        } else {
            $hours = floor($durationInMin / 60);
            $minutes = floor($durationInMin % 60);

            $duration = $hours . ' ' . ($this->locale === 'ru' ? 'ч' : 'h');
            if ($minutes) {
                $duration .= $minutes . ' ' . ($this->locale === 'ru' ? 'мин' : 'min');
            }
        }

        return trim($duration);
    }

    /**
     * @param string|null $days
     *
     * @return string|null
     */
    private function formatDays(?string $days): ?string
    {
        if ($days === null || $this->locale === 'ru') {
            return $days;
        }

        switch ($days) {
            case 'ежедневно':
                $days = 'daily';
                break;
            case 'по будням':
                $days = 'on weekdays';
                break;
            case 'кроме вс':
                $days = 'except sun';
                break;
        }

        return $days;
    }
}