<?php


namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostTypeForm;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;


class PostController extends AbstractController
{
    /**
     * @Route("/post",name="posts")
     */
    public function index(PostsRepository $repository) {
        $posts = $repository->findAndOrderByDate();
        return $this->render('post/index.html.twig', ['posts'=>$posts]);
    }

    /**
     * @Route("/post/new",name="post_new")
     */
    public function new(Request $request, EntityManagerInterface $manager) {
        $post = new Posts(); /*obiekt*/
        $form = $this->createForm(PostTypeForm::class, $post); /*stworzyliśmy zmienną formularza na podstawie PostTypeForm i wkazałyśmy jej obiekt*/
        $form->handleRequest($request); /*przechwycimy request, eby wsadzić go do obiektu*/

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($post);
            $manager->flush(); /*jak formularz jest ok, to obiekt leci w bazę*/
        }

        return $this->render('post/form.html.twig', ['form'=>$form->createView()]); /*wygenerowanie widoku i prekazanie widoku formularza, który się sam robi, bo symfony jest mondre.*/
    }

    /**
     * @Route("/post/{id}/delete",name="post_delete", methods={"GET","DELETE"})
     */
    public function delete(PostsRepository $repository, Request $request, Posts $post) {
        $form = $this->createForm(FormType::class, $post, ['method'=>'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($post);
            return $this->redirectToRoute("posts");
        }

        return $this->render('post/delete.html.twig', ['form'=>$form->createView(), "post"=>$post]);
    }

}