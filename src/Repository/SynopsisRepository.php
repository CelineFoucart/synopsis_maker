<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Synopsis;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Synopsis>
 *
 * @method Synopsis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Synopsis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Synopsis[]    findAll()
 * @method Synopsis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SynopsisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Synopsis::class);
    }
    
    public function findPaginated(User $user, array $params): SlidingPagination
    {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $limit = isset($params['limit']) ? (int)$params['limit'] : 10;
        $search = isset($params['query']) ? $params['query'] : null;

        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.author', 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $user->getId())
        ;

        if ($search) {
            $search = str_replace(' ', '%', $search);
            $query->andWhere('s.title LIKE :search')->setParameter('search', '%' . $search . '%');
        }

        $query->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }

//    public function findOneBySlug(string $slug): ?Synopsis
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.slug = :slug')
//            ->setParameter('slug', $slug)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
