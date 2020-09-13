<?php

/**
 * PostsRepository
 */
namespace App\Repository;

use App\Entity\Category;
use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

/**
 * Class PostsRepository
 * @package App\Repository
 */
class PostsRepository extends ServiceEntityRepository
{


    /**
     * PostsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

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

    /**
     * finds and order records by date
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

    /**
     * save function
     * @param Posts $post
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Posts $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush($post);
    }

    /**
     * delete function
     * @param Posts $post
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */

    public function delete(Posts $post): void
    {
        $this->_em->remove($post);
        $this->_em->flush($post);
    }

    /**
     * List of posts
     * @param Category $categoryId
     * @return QueryBuilder
     */
    public function postList(Category $categoryId): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()

            ->select(
                'partial posts.{id, title, content, createdAt}',
                'partial category.{id}'
            )
            ->join('posts.category', 'category')
            ->andWhere('posts.category=:categoryId')
            ->setParameter('categoryId', $categoryId);

        return $queryBuilder;
    }

    /**
     * Comments under post
     * @param Posts $post
     * @return QueryBuilder
     */

    public function PostComments(Posts $post): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial posts.{id, title, content, createdAt}',
                'partial comments.{id, nick, email, content}'
            )
        ->join('posts.comments', 'comments')
        ->andWhere('posts.comments=:post')
        ->setParameter('post', $post);

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('posts');
    }
}
