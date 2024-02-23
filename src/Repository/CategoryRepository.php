<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Category objects
     */
    public function findByAuthor(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author = :user')
            ->setParameter('user', $user)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param integer[] $ids
     * @param User $user
     * 
     * @return Category[] Returns an array of Category objects
     */
    public function findByIds(array $ids, User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author = :user')
            ->andWhere('c.id IN (:ids)')
            ->setParameter('user', $user)
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }
}
