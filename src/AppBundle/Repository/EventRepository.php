<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EventRepository extends EntityRepository
{
//    public function __construct(RegistryInterface $registry) {
//        parent::__construct($registry, Event::class);
//    }

    /**
     * Récupère les évènements commençant entre 2 dates
     */
    public function getEventsBetween($dayStart, $dayEnd) {

        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.date > :dayStart')
            ->andWhere('e.date < :dayEnd')
            ->setParameter('dayStart', $dayStart)
            ->setParameter('dayEnd', $dayEnd)
            ->orderBy('e.start', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    public function getEventById($id_event) {

        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.id = :id_event')
            ->setParameter('id_event', $id_event)
            ->getQuery();

        return $qb->execute();
    }
}