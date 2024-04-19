<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Place;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Place>
 *
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    /**
     * @return Place[] Returns an array of Place objects
     */
    public function findByAuthor(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.author' , 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $user->getId())
            ->orderBy('p.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIds(array $ids, User $user): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.author' , 'u')
            ->andWhere('u.id = :id')->setParameter('id', $user->getId())
            ->andWhere('p.id IN (:ids)')->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }
}
