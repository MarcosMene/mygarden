<?php

namespace App\Entity;

use App\Repository\StoreScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoreScheduleRepository::class)]
#[UniqueEntity('day')]
class StoreSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $openTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $closeTime = null;

    #[ORM\Column]
    private ?int $dayOrder = null;

    private const DAY_MAP = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 7,
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        // AUTOMATICALLY SET THE DAYORDER BASED ON THE NAME
        if (array_key_exists($day, self::DAY_MAP)) {
            $this->dayOrder = self::DAY_MAP[$day];
        }

        return $this;
    }

    public function getOpenTime(): ?\DateTimeInterface
    {
        return $this->openTime;
    }

    public function setOpenTime(\DateTimeInterface $openTime): static
    {
        $this->openTime = $openTime;
        return $this;
    }

    public function getCloseTime(): ?\DateTimeInterface
    {
        return $this->closeTime;
    }

    public function setCloseTime(\DateTimeInterface $closeTime): static
    {
        $this->closeTime = $closeTime;
        return $this;
    }

    public function getDayOrder(): ?int
    {
        return $this->dayOrder;
    }

    public function setDayOrder(int $dayOrder): static
    {
        $this->dayOrder = $dayOrder;

        return $this;
    }
}
