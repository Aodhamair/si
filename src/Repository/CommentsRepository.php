<?php

/**
 * commentsRepository
 */
namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

/**
 * Class CommentsRepository
 */
class CommentsRepository extends ServiceEntityRepository
{

    /**
     * CommentsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * save comments
     * @param Comments $comment
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveComment(Comments $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush($comment);
    }

    /**
     * delete comments
     * @param Comments $comment
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteComment(Comments $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush($comment);
    }
}
