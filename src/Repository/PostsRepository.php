<?php

namespace App\Repository;

use App\Entity\Posts;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{

    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;



    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    /**
      * @return Posts[] Returns an array of Posts objects
      */

    public function findAndOrderByDate()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(Posts $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush($post);
    }

    public function delete(Posts $post): void
    {
        $this->_em->remove($post);
        $this->_em->flush($post);
    }


    public function postList(Category $categoryId) : QueryBuilder
    {
//        return $post->createQueryBuilder('c')
//
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $categoryId)
//            ->getQuery()
//            ->getResult()
//            ;
        $queryBuilder = $this->createQueryBuilder('p')

//            ->join('c.Id','posts','with','Posts.category= :categoryId')
            ->join('App\Entity\Category','c')
            ->andWhere('p.category=:categoryId')
            ->setParameter('categoryId', $categoryId);




        return $queryBuilder;

    }


    /*
    public function findOneBySomeField($value): ?Posts
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


}
