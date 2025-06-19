<?php

namespace App\EventSubscriber;

use App\Entity\Booking;
use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::postUpdate, entity: Booking::class)]
#[AsEntityListener(event: Events::postPersist, entity: Booking::class)]
#[AsEntityListener(event: Events::postRemove, entity: Booking::class)]
readonly class BookingChangedNotifier
{

    public function __construct(private Security $security)
    {
    }

    public function postUpdate(Booking $booking, PostUpdateEventArgs $event): void
    {
        $this->handleEvent($event, 'modifiée');
    }

    public function postPersist(Booking $booking, PostPersistEventArgs $event): void
    {
        $this->handleEvent($event, 'crée');
    }

    public function postRemove(Booking $booking, PostPersistEventArgs $event): void
    {
        $this->handleEvent($event, 'supprimée');

    }

    private function handleEvent(LifecycleEventArgs $args, string $action): void
    {

        /**
         * @var $entity Booking
         */
        $entity = $args->getObject();

        $em = $args->getObjectManager();

        $notification = new Notification();
        $notification->setTitle('Réservation ' . $action);
        $notification->setMessage(sprintf(
            'La réservation #%d a été %s par %s.',
            $entity->getId(),
            $action,
            $this->security->getUser() ? $this->security->getUser()->getUserIdentifier() : 'un utilisateur'
        ));
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setBooking($entity);
        $notification->setUser($this->security->getUser());

        $em->persist($notification);
        $em->flush();
    }
}
