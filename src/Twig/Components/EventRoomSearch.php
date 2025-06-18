<?php

namespace App\Twig\Components;

use App\Repository\EventRoomRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('EventRoomSearch', template: 'components/EventRoomSearch.html.twig')]
final class EventRoomSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;

    public function __construct(private EventRoomRepository $errepo) {}

    public function getEventRooms(): array
    {
        if ($this->query) {
            return $this->errepo->findByName($this->query);
        }

        return $this->errepo->findAll();
    }
}
