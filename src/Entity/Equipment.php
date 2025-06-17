<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, EventRoom>
     */
    #[ORM\ManyToMany(targetEntity: EventRoom::class, mappedBy: 'equipments')]
    private Collection $eventRooms;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    public function __construct()
    {
        $this->eventRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, EventRoom>
     */
    public function getEventRooms(): Collection
    {
        return $this->eventRooms;
    }

    public function addEventRoom(EventRoom $eventRoom): static
    {
        if (!$this->eventRooms->contains($eventRoom)) {
            $this->eventRooms->add($eventRoom);
            $eventRoom->addEquipment($this);
        }

        return $this;
    }

    public function removeEventRoom(EventRoom $eventRoom): static
    {
        if ($this->eventRooms->removeElement($eventRoom)) {
            $eventRoom->removeEquipment($this);
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
}
