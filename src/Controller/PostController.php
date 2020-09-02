<?php


namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Comments;
use App\Form\PostTypeForm;
use App\Repository\PostsRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CommentTypeForm;


/**
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    /**
     *
     *  * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\PostRepository            $repository PostRepository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/",name="posts")
     */
    public function index(PostsRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
             $repository->findAndOrderByDate(),
             $request->query->getInt('page', 1),
             PostsRepository::PAGINATOR_ITEMS_PER_PAGE,
    );
        return $this->render('post/index.html.twig',
            ['pagination' => $pagination]);
    }


    /**
     * @Route("/new",name="post_new")
     */
    public function new(Request $request, PostsRepository $repository)
    {
        $post = new Posts(); /*obiekt*/
        $form = $this->createForm(PostTypeForm::class, $post); /*stworzyliśmy zmienną formularza na podstawie PostTypeForm i wkazałyśmy jej obiekt*/
        $form->handleRequest($request); /*przechwycimy request, eby wsadzić go do obiektu*/

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($post);
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute("posts");
        }

        return $this->render('post/form.html.twig', ['form'=>$form->createView()]); /*wygenerowanie widoku i prekazanie widoku formularza, który się sam robi, bo symfony jest mondre.*/
    }

    /**
     * @Route("/{id}",
     *     name="post_show",
     *     methods={"GET","POST"},
     *     requirements={"id": "[1-9]\d*"})
     */
    public function show(Posts $post, PostsRepository $postRepository, Request $request, CommentsRepository $commentRepository): Response
    {

        $comments = $postRepository->PostComments($post);
        dump($comments);

        $comment = new Comments(); /*obiekt*/
        $comment->setPost($post);
        $form = $this->createForm(CommentTypeForm::class, $comment);
        $form->handleRequest($request); /*przechwycimy request, eby wsadzić go do obiektu*/

        if ($form->isSubmitted() && $form->isValid()) {

            $commentRepository->saveComment($comment);
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute("posts");
        }

        return $this->render(
            'post/show.html.twig',
            ['post' => $post,
            'comments' => $comments,
            'form'=>$form->createView()]
        );
    }

    /**
     * @Route("/{id}/delete",name="post_delete", methods={"GET","DELETE"})
     */
    public function delete(PostsRepository $repository, Request $request, Posts $post)
    {
        $form = $this->createForm(FormType::class, $post, ['method'=>'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($post);
            $this->addFlash('success', 'message_deleted_successfully');
            return $this->redirectToRoute("posts");
        }

        return $this->render('post/delete.html.twig', ['form'=>$form->createView(), "post"=>$post]);
    }

    /**
     * @Route("/{id}/edit",name="post_edit", methods={"GET","PUT"})
     */
    public function edit(PostsRepository $repository, Request $request, Posts $post)
    {
        $form = $this->createForm(PostTypeForm::class, $post, ['method'=>'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($post);
            $this->addFlash('success', 'message_edited_successfully');
            return $this->redirectToRoute("posts");
        }

        return $this->render('post/form.html.twig', ['form'=>$form->createView()]);
    }



}