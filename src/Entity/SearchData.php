<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SearchDataRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=SearchDataRepository::class)
 * @ORM\Table(
 *     name="search_data",
 *     indexes={
 *         @ORM\Index(columns={"data"}, flags={"fulltext"})
 *     }
 * )
 */
class SearchData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $object_id = null;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $object_type_id = null;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $data = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $last_update = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getObjectId(): ?int
    {
        return $this->object_id;
    }

    /**
     * @param int $object_id
     *
     * @return $this
     */
    public function setObjectId(int $object_id): self
    {
        $this->object_id = $object_id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getObjectType(): ?int
    {
        return $this->object_type_id;
    }

    /**
     * @param int $objectTypeId
     *
     * @return $this
     */
    public function setObjectType(int $objectTypeId): self
    {
        $this->object_type_id = $objectTypeId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return $this
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getLastUpdate(): ?DateTimeImmutable
    {
        return $this->last_update;
    }

    /**
     * @param DateTimeImmutable $lastUpdate
     *
     * @return $this
     */
    public function setLastUpdate(\DateTimeImmutable $lastUpdate): self
    {
        $this->last_update = $lastUpdate;

        return $this;
    }
}
