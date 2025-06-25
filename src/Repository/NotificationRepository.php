<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }
    public function countUnreadForUser(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->andWhere('n.isRead = false')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
