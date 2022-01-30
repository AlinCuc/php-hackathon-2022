<?php

namespace App\Repository;

use App\Entity\Programme;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Programme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programme[]    findAll()
 * @method Programme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

    public function findOverlappingProgramme(\DateTime $startTime, \DateTime $endTime, Room $room)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.startTime < :end_time')
            ->setParameter('end_time', $endTime)
            ->andWhere('p.endTime > :start_time')
            ->setParameter('start_time', $startTime)
            ->andWhere('p.room = :room')
            ->setParameter('room', $room)
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }
}
