<?php
/**
 * Posts service.
 */

namespace App\Service;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostsService
 */
class PostsService
{
    /**
     * Posts repository.
     *
     * @var \App\Repository\PostsRepository
     */
    private $postsRepository;

    /**
     * Comments repository.
     *
     * @var \App\Repository\CommentsRepository
     */
    private $commentsRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * PostsService constructor.
     * @param PostsRepository    $postsRepository
     * @param CommentsRepository $commentsRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(PostsRepository $postsRepository, CommentsRepository $commentsRepository, PaginatorInterface $paginator)
    {
        $this->commentsRepository = $commentsRepository;
        $this->postsRepository = $postsRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postsRepository->findAndOrderByDate(),
            $page,
            PostsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save post.
     *
     * @param Posts $post post post
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Posts $post): void
    {
        $this->postsRepository->save($post);
    }

    /**
     * Delete post.
     *
     * @param \App\Entity\Posts $post Posts entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Posts $post): void
    {
        $this->postsRepository->delete($post);
    }

    /**
     * Save comment.
     *
     * @param Comments $comment comments comment
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveComment(Comments $comment): void
    {
        $this->commentsRepository->saveComment($comment);
    }

    /**
     * Function callPostsComments.
     * @param Posts $post
     *
     * @return QueryBuilder
     */
    public function callPostComments(Posts $post): QueryBuilder
    {
        return $this->postsRepository->PostComments($post);
    }

    /**
     * Delete comment.
     *
     * @param \App\Entity\Comments $comment Comments entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteComment(Comments $comment): void
    {
        $this->commentsRepository->deleteComment($comment);
    }
}
