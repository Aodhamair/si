<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryTypeForm;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;


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
     * @Route("/{id}/delete",name="category_delete", methods={"GET","DELETE"})
     */
    public function delete(CategoryRepository $repository, Request $request, Category $category)
    {
        $form = $this->createForm(FormType::class, $category, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($category);
            $this->addFlash('success', 'message_deleted_successfully');
            return $this->redirectToRoute("category");
        }

        return $this->render('category/delete.html.twig', ['form' => $form->createView(), "category" => $category]);
    }


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









}