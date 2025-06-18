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
    public function findByFilters(?string $query, array $equipmentIds, array $criteriaIds, array $softwareIds): array
    {
        $qb = $this->createQueryBuilder('er')
            ->leftJoin('er.equipments', 'e')
            ->leftJoin('er.ergonomicCriterias', 'c')
            ->leftJoin('er.softwares', 's');

        if ($query) {
            $qb->andWhere('LOWER(er.name) LIKE :query')
                ->setParameter('query', '%' . strtolower($query) . '%');
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

        return $qb->getQuery()->getResult();
    }
}
