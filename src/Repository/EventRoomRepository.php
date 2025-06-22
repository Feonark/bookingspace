<?php

namespace App\Repository;

use App\Entity\EventRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventRoom>
 */
class EventRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventRoom::class);
    }

    /**
     * @return EventRoom[] Returns an array of EventRoom objects
     */
    public function findByFilters(
        ?string $query,
        array $equipmentIds,
        array $criteriaIds,
        array $softwareIds,
        ?string $dateStart = null,
        ?string $dateEnd = null,
        ?int $capacity = null
    ): array {

        $qb = $this->createQueryBuilder('er')
            ->leftJoin('er.equipments', 'e')
            ->leftJoin('er.ergonomicCriterias', 'c')
            ->leftJoin('er.softwares', 's');

        if ($query) {
            $qb->andWhere('LOWER(er.name) LIKE :query')
                ->setParameter('query', '%' . strtolower($query) . '%');
        }

        if ($capacity !== null) {
            $qb->andWhere('er.capacity >= :capacity')
                ->setParameter('capacity', $capacity);
        }

        $qb->groupBy('er.id');

        if (!empty($equipmentIds)) {
            $qb->andWhere('e.id IN (:equipmentIds)')
                ->setParameter('equipmentIds', $equipmentIds)
                ->having('COUNT(DISTINCT e.id) = :equipmentCount')
                ->setParameter('equipmentCount', count($equipmentIds));
        }

        if (!empty($criteriaIds)) {
            $qb->andWhere('c.id IN (:criteriaIds)')
                ->setParameter('criteriaIds', $criteriaIds)
                ->andHaving('COUNT(DISTINCT c.id) = :criteriaCount')
                ->setParameter('criteriaCount', count($criteriaIds));
        }

        if (!empty($softwareIds)) {
            $qb->andWhere('s.id IN (:softwareIds)')
                ->setParameter('softwareIds', $softwareIds)
                ->andHaving('COUNT(DISTINCT s.id) = :softwareCount')
                ->setParameter('softwareCount', count($softwareIds));
        }

        if ($dateStart && $dateEnd) {
            $now = new \DateTime('today');
            $startDateTime = new \DateTime($dateStart);
            $endDateTime = new \DateTime($dateEnd);

            if ($startDateTime < $now || $endDateTime < $now) {
                return [];
            }

            if ($startDateTime > $endDateTime) {
                return [];
            }

            $endDateTime->setTime(23, 59, 59);

            $subQb = $this->getEntityManager()->createQueryBuilder();
            $subQb->select('b2.id')
                ->from('App\Entity\Booking', 'b2')
                ->where('b2.eventRoom = er')
                ->andWhere('(:dateStart <= b2.dateEnd AND :dateEnd >= b2.dateStart)')
                ->andWhere('b2.bookingStatus IN (:activeStatuses)');

            $qb->andWhere($qb->expr()->not(
                $qb->expr()->exists($subQb->getDQL())
            ))
                ->setParameter('dateStart', $startDateTime)
                ->setParameter('dateEnd', $endDateTime)
                ->setParameter('activeStatuses', [
                    \App\Enum\BookingStatus::CONFIRMED,
                    \App\Enum\BookingStatus::PENDING,
                ]);
        }

        return $qb->getQuery()->getResult();
    }
}
