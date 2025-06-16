<?php

namespace App\Entity;

use App\Enum\BookingStatus;
use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $app_user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateEnd = null;

    #[ORM\Column(enumType: BookingStatus::class)]
    private ?BookingStatus $bookingStatus = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventRoom $eventRoom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppUser(): ?User
    {
        return $this->app_user;
    }

    public function setAppUser(?User $app_user): static
    {
        $this->app_user = $app_user;

        return $this;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTime $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTime $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getBookingStatus(): ?BookingStatus
    {
        return $this->bookingStatus;
    }

    public function setBookingStatus(BookingStatus $bookingStatus): static
    {
        $this->bookingStatus = $bookingStatus;

        return $this;
    }

    public function getEventRoom(): ?EventRoom
    {
        return $this->eventRoom;
    }

    public function setEventRoom(?EventRoom $eventRoom): static
    {
        $this->eventRoom = $eventRoom;

        return $this;
    }
}
