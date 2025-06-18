<?php

namespace App\Twig\Components;

use App\Repository\EquipmentRepository;
use App\Repository\ErgonomicCriteriaRepository;
use App\Repository\EventRoomRepository;
use App\Repository\SoftwareRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('EventRoomSearch', template: 'components/EventRoomSearch.html.twig')]
final class EventRoomSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;

    #[LiveProp(writable: true)]
    public ?array $selected_equipments = [];

    #[LiveProp(writable: true)]
    public ?array $selected_ergonomic_criterias = [];

    #[LiveProp(writable: true)]
    public ?array $selected_softwares = [];

    public function __construct(
        private EventRoomRepository $errepo,
        private EquipmentRepository $eqrepo,
        private ErgonomicCriteriaRepository $ecrepo,
        private SoftwareRepository $swrepo,
    ) {}

    public function getEventRooms(): array
    {
        // if ($this->query || !empty($this->selectedEquipments)) {
        return $this->errepo->findByFilters(
            $this->query,
            $this->selected_equipments,
            $this->selected_ergonomic_criterias,
            $this->selected_softwares
        );
        // }

        return $this->errepo->findAll();
    }

    public function getEquipments(): array
    {
        return $this->eqrepo->findAll();
    }

    public function getErgonomicCriterias(): array
    {
        return $this->ecrepo->findAll();
    }

    public function getSoftwares(): array
    {
        return $this->swrepo->findAll();
    }
}
