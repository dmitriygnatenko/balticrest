<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 * @ORM\Table(
 *     name="schedule",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"station_from", "station_to"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $station_from = null;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $station_to = null;

    /**
     * @ORM\Column(type="array")
     */
    private array $data = [];

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $last_update_time = null;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(): void
    {
        $this->last_update_time = new DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Schedule
     */
    public function setId(int $id): Schedule
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStationFrom(): ?string
    {
        return $this->station_from;
    }

    /**
     * @param string $stationFrom
     *
     * @return Schedule
     */
    public function setStationFrom(string $stationFrom): Schedule
    {
        $this->station_from = $stationFrom;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStationTo(): ?string
    {
        return $this->station_to;
    }

    /**
     * @param string $stationTo
     *
     * @return Schedule
     */
    public function setStationTo(string $stationTo): Schedule
    {
        $this->station_to = $stationTo;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return Schedule
     */
    public function setData(array $data): Schedule
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getLastUpdateTime(): ?DateTimeImmutable
    {
        return $this->last_update_time;
    }

    /**
     * @param DateTimeImmutable $lastUpdateTime
     *
     * @return Schedule
     */
    public function setLastUpdateTime(DateTimeImmutable $lastUpdateTime): Schedule
    {
        $this->last_update_time = $lastUpdateTime;

        return $this;
    }
}
