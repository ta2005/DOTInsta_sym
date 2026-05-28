<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
    public function findFeedForUser(User $user): array
    {
        return $this->createQueryBuilder('p')
        ->join('p.group_id', 'g') // Join the Group
        ->join('g.membreGroupes', 'm') // Join the Members of that Group
        ->where('m.user_id = :user') // Filter by the current user
        ->setParameter('user', $user)
        ->orderBy('p.dateCreation', 'DESC') // Newest posts first!
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
        //        return $this->createQueryBuilder('p')
        //            ->andWhere('p.exampleField = :val')
        //            ->setParameter('val', $value)
        //            ->orderBy('p.id', 'ASC')
        //            ->setMaxResults(10)
        //            ->getQuery()
        //            ->getResult()
        //        ;
        //    }

        //    public function findOneBySomeField($value): ?Post
        //    {
            //        return $this->createQueryBuilder('p')
            //            ->andWhere('p.exampleField = :val')
            //            ->setParameter('val', $value)
            //            ->getQuery()
            //            ->getOneOrNullResult()
            //        ;
            //    }
        }
