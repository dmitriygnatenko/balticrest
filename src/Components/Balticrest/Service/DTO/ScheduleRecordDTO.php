<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class ScheduleRecordDTO
{
    private ?string $departure = null;

    private ?string $arrival = null;

    private ?string $duration = null;

    private ?string $days = null;

    private ?string $number = null;

    /**
     * @return string|null
     */
    public function getDeparture(): ?string
    {
        return $this->departure;
    }

    /**
     * @param string|null $departure
     *
     * @return ScheduleRecordDTO
     */
    public function setDeparture(?string $departure): ScheduleRecordDTO
    {
        $this->departure = $departure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getArrival(): ?string
    {
        return $this->arrival;
    }

    /**
     * @param string|null $arrival
     *
     * @return ScheduleRecordDTO
     */
    public function setArrival(?string $arrival): ScheduleRecordDTO
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @param string|null $duration
     *
     * @return ScheduleRecordDTO
     */
    public function setDuration(?string $duration): ScheduleRecordDTO
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDays(): ?string
    {
        return $this->days;
    }

    /**
     * @param string|null $days
     *
     * @return ScheduleRecordDTO
     */
    public function setDays(?string $days): ScheduleRecordDTO
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string|null $number
     *
     * @return ScheduleRecordDTO
     */
    public function setNumber(?string $number): ScheduleRecordDTO
    {
        $this->number = $number;

        return $this;
    }
}