<?php

namespace App\Repository;

use App\Entity\WorldBuildingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorldBuildingCategory>
 *
 * @method WorldBuildingCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorldBuildingCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorldBuildingCategory[]    findAll()
 * @method WorldBuildingCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorldBuildingCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorldBuildingCategory::class);
    }

//    /**
//     * @return WorldBuildingCategory[] Returns an array of WorldBuildingCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorldBuildingCategory
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
