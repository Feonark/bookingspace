<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * Retourne toutes les réservations entre deux dates.
     */
    public function findBetween(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.startTime < :end')
            ->andWhere('b.endTime > :start')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les réservations d’un utilisateur donné.
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Détecte les conflits de réservation pour une salle donnée sur un créneau horaire.
     * (utile pour création ou modification)
     */
    public function findConflictingBookings(int $roomId, \DateTimeInterface $start, \DateTimeInterface $end, ?int $excludeId = null): array
    {
        $qb = $this->createQueryBuilder('b')
            ->andWhere('b.room = :roomId')
            ->andWhere('b.startTime < :end')
            ->andWhere('b.endTime > :start')
            ->setParameter('roomId', $roomId)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        if ($excludeId !== null) {
            $qb->andWhere('b.id != :excludeId')
                ->setParameter('excludeId', $excludeId);
        }

        return $qb->getQuery()->getResult();
    }

    public function getUserBookings(UserInterface $user)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
