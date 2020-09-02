<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryTypeForm;
use App\Repository\CategoryRepository;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/",name="category")
     */
    public function index(CategoryRepository $repository)
    {
        $categories = $repository->findAll();
        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }



    /**
     * @Route("/new",name="category_new")
     */
    public function new(Request $request, CategoryRepository $repository)
    {
        $category = new Category(); /*obiekt*/
        $form = $this->createForm(CategoryTypeForm::class, $category); /*stworzyliśmy zmienną formularza na podstawie CtegoryTypeForm i wkazałyśmy jej obiekt*/
        $form->handleRequest($request); /*przechwycimy request, żeby wsadzić go do obiektu*/

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute("category");
        }

        return $this->render('category/form.html.twig', ['form'=>$form->createView()]); /*wygenerowanie widoku i prekazanie widoku formularza, który się sam robi, bo symfony jest mondre.*/
    }

//    /**
//     * @Route("/{id}/delete",name="category_delete", methods={"GET","DELETE"})
//     */
//    public function delete(CategoryRepository $repository, Request $request, Category $category)
//    {
//        $form = $this->createForm(FormType::class, $category, ['method' => 'DELETE']);
//        $form->handleRequest($request);
//
//        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
//            $form->submit($request->request->get($form->getName()));
//        }
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $repository->delete($category);
//            $this->addFlash('success', 'message_deleted_successfully');
//            return $this->redirectToRoute("category");
//        }
//
//        return $this->render('category/delete.html.twig', ['form' => $form->createView(), "category" => $category]);
//    }
//    USUWANIE KATEGORII, NIE CHCEMY GO W FUNKCJONALNOŚCI
//

    /**
     * @Route("/{id}/edit",name="category_edit", methods={"GET","PUT"})
     */
    public function edit(CategoryRepository $repository, Request $request, Category $category)
    {
        $form = $this->createForm(CategoryTypeForm::class, $category, ['method'=>'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);
            $this->addFlash('success', 'message_edited_successfully');
            return $this->redirectToRoute("category");
        }

        return $this->render('category/form.html.twig', ['form'=>$form->createView()]);
    }


    /**
     * @Route("/{id}",name="category_posts", methods={"GET"}, requirements={"id":"[1-9]\d*"})
     */
    public function showPosts(Category $category, PostsRepository $repository, Request $request,  PaginatorInterface $paginator) : Response
    {
        dump($repository->postList($category));


            $pagination = $paginator->paginate(
            $repository->postList($category),
            $request->query->getInt('page', 1),
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE,
    );
        dump($pagination);
        return $this->render('category/post_category.html.twig',
            ['pagination' => $pagination]);
    }







}