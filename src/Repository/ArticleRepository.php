<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    
    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByAuthor(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.author' , 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $user->getId())
            ->orderBy('a.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
