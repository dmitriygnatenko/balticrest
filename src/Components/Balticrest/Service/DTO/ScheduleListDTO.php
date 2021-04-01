<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

use DateTimeInterface;

class ScheduleListDTO
{

    private ?string $from_code = null;

    private ?string $from_title = null;

    private ?string $to_code = null;

    private ?string $to_title = null;

    private ?DateTimeInterface $last_update = null;

    /** @var ScheduleRecordDTO[] */
    private array $records = [];

    /**
     * @return string|null
     */
    public function getFromTitle(): ?string
    {
        return $this->from_title;
    }

    /**
     * @param string|null $fromTitle
     *
     * @return ScheduleListDTO
     */
    public function setFromTitle(?string $fromTitle): ScheduleListDTO
    {
        $this->from_title = $fromTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToTitle(): ?string
    {
        return $this->to_title;
    }

    /**
     * @param string|null $toTitle
     *
     * @return ScheduleListDTO
     */
    public function setToTitle(?string $toTitle): ScheduleListDTO
    {
        $this->to_title = $toTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFromCode(): ?string
    {
        return $this->from_code;
    }

    /**
     * @param string|null $fromCode
     *
     * @return ScheduleListDTO
     */
    public function setFromCode(?string $fromCode): ScheduleListDTO
    {
        $this->from_code = $fromCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToCode(): ?string
    {
        return $this->to_code;
    }

    /**
     * @param string|null $toCode
     *
     * @return ScheduleListDTO
     */
    public function setToCode(?string $toCode): ScheduleListDTO
    {
        $this->to_code = $toCode;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastUpdate(): ?DateTimeInterface
    {
        return $this->last_update;
    }

    /**
     * @param DateTimeInterface|null $lastUpdate
     *
     * @return ScheduleListDTO
     */
    public function setLastUpdate(?DateTimeInterface $lastUpdate): ScheduleListDTO
    {
        $this->last_update = $lastUpdate;

        return $this;
    }

    /**
     * @return ScheduleRecordDTO[]
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * @param ScheduleRecordDTO $recordDTO
     *
     * @return $this
     */
    public function addRecord(ScheduleRecordDTO $recordDTO): ScheduleListDTO
    {
        $this->records[] = $recordDTO;

        return $this;
    }
}
