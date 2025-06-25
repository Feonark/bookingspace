<?php

namespace App\Entity;

use App\Enum\BookingStatus;
use App\EventSubscriber\BookingChangedNotifier;
use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateEnd = null;

    #[ORM\Column(enumType: BookingStatus::class)]
    private ?BookingStatus $bookingStatus = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventRoom $eventRoom = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'booking', orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $reminderNotificationCreated = false;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable:true)]
    private ?\DateTime $reminderNotificationSentAt = null;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppUser(): ?User
    {
        return $this->user;
    }

    public function setAppUser(?User $user): static
    {
        $this->user = $user;

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

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setBooking($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getBooking() === $this) {
                $notification->setBooking(null);
            }
        }

        return $this;
    }

    public function isReminderNotificationCreated(): bool
    {
        return $this->reminderNotificationCreated;
    }

    public function setReminderNotificationCreated(bool $reminderNotificationCreated): self
    {
        $this->reminderNotificationCreated = $reminderNotificationCreated;

        return $this;
    }

    public function getReminderNotificationSentAt(): ?\DateTime
    {
        return $this->reminderNotificationSentAt;
    }

    public function setReminderNotificationSentAt(?\DateTime $reminderNotificationSentAt): void
    {
        $this->reminderNotificationSentAt = $reminderNotificationSentAt;
    }
}
