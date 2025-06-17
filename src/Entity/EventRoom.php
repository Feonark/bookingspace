<?php

namespace App\Entity;

use App\Repository\EventRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRoomRepository::class)]
class EventRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'eventRoom', orphanRemoval: true)]
    private Collection $bookings;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $capacity = null;

    /**
     * @var Collection<int, Software>
     */
    #[ORM\ManyToMany(targetEntity: Software::class, inversedBy: 'eventRooms')]
    private Collection $softwares;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\ManyToMany(targetEntity: Equipment::class, inversedBy: 'eventRooms')]
    private Collection $equipments;

    /**
     * @var Collection<int, ErgonomicCriteria>
     */
    #[ORM\ManyToMany(targetEntity: ErgonomicCriteria::class, inversedBy: 'eventRooms')]
    private Collection $ergonomicCriterias;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->softwares = new ArrayCollection();
        $this->equipments = new ArrayCollection();
        $this->ergonomicCriterias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setEventRoom($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getEventRoom() === $this) {
                $booking->setEventRoom(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection<int, Software>
     */
    public function getSoftwares(): Collection
    {
        return $this->softwares;
    }

    public function addSoftware(Software $software): static
    {
        if (!$this->softwares->contains($software)) {
            $this->softwares->add($software);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        $this->softwares->removeElement($software);

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        $this->equipments->removeElement($equipment);

        return $this;
    }

    /**
     * @return Collection<int, ErgonomicCriteria>
     */
    public function getErgonomicCriterias(): Collection
    {
        return $this->ergonomicCriterias;
    }

    public function addErgonomicCriteria(ErgonomicCriteria $ergonomicCriteria): static
    {
        if (!$this->ergonomicCriterias->contains($ergonomicCriteria)) {
            $this->ergonomicCriterias->add($ergonomicCriteria);
        }

        return $this;
    }

    public function removeErgonomicCriteria(ErgonomicCriteria $ergonomicCriteria): static
    {
        $this->ergonomicCriterias->removeElement($ergonomicCriteria);

        return $this;
    }
}
