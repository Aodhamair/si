<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentTypeForm;
use App\Form\PostTypeForm;
use App\Repository\PostsRepository;
use App\Service\PostsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    /**
     * Posts service.
     *
     * @var \App\Service\PostsService
     */
    private $postsService;

    /**
     * PostController constructor.
     *
     * @param \App\Service\PostsService $postsService Posts service
     */
    public function __construct(PostsService $postsService)
    {
        $this->postsService = $postsService;
    }

    /**
     *  * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PostRepository            $repository PostRepository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/",name="posts")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->postsService->createPaginatedList($page);

        return $this->render(
            'post/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * New Post
     * @Route("/new",name="post_new")
     */
    public function new(Request $request, PostsRepository $repository)
    {
        $post = new Posts();
        $form = $this->createForm(PostTypeForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($post);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render('post/form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}",
     *     name="post_show",
     *     methods={"GET","POST"},
     *     requirements={"id": "[1-9]\d*"})
     */
    public function show(Posts $post, PostsRepository $postRepository, Request $request): Response
    {
        $comments = $postRepository->PostComments($post);
        $comment = new Comments();
        $comment->setPost($post);
        $form = $this->createForm(CommentTypeForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postsService->saveComment($comment);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render(
            'post/show.html.twig',
            ['post' => $post,
            'comments' => $comments,
            'form' => $form->createView(), ]
        );
    }

    /**
     * @Route("/{id}/delete",name="post_delete", methods={"GET","DELETE"})
     */
    public function delete(Request $request, Posts $post)
    {
        $form = $this->createForm(FormType::class, $post, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postsService->delete($post);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render('post/delete.html.twig', ['form' => $form->createView(), 'post' => $post]);
    }

    /**
     * @Route("/{id}/edit",name="post_edit", methods={"GET","PUT"})
     */
    public function edit(Request $request, Posts $post)
    {
        $form = $this->createForm(PostTypeForm::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postsService->save($post);
            $this->addFlash('success', 'message_edited_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render('post/form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/commentdelete",name="comment_delete", methods={"GET","DELETE"})
     */
    public function deleteComment(Request $request, Comments $comment)
    {
        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postsService->deleteComment($comment);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render('post/commentdelete.html.twig', ['form' => $form->createView(), 'comment' => $comment]);
    }
}
