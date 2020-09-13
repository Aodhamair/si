<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryTypeForm;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\PostsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * Posts service.
     *
     * @var \App\Service\PostsService
     */
    private $postsService;

    /**
     * CategoryController constructor.
     *
     * @param \App\Service\CategoryService $categoryService Category service
     * @param \App\Service\PostsService    $postsService    Posts service
     */
    public function __construct(CategoryService $categoryService, PostsService $postsService)
    {
        $this->categoryService = $categoryService;
        $this->postsService = $postsService;
    }

    /**
     * @Route("/",name="category")
     */
    public function index(CategoryRepository $repository)
    {
        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    /**
     * Adding new category
     * @Route("/new",name="category_new")
     */
    public function new(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryTypeForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('category');
        }

        return $this->render('category/form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit",name="category_edit", methods={"GET","PUT"})
     */
    public function edit(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryTypeForm::class, $category, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', 'message_edited_successfully');

            return $this->redirectToRoute('category');
        }

        return $this->render('category/form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}",name="category_posts", methods={"GET"}, requirements={"id":"[1-9]\d*"})
     */
    public function showPosts(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->postsService->createPaginatedList($page);

        return $this->render(
            'category/post_category.html.twig',
            ['pagination' => $pagination]
        );
    }
}
