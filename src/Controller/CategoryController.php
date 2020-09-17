<?php
/**
 * Category controller
 */
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
     * Adding new category
     * @Route("/",name="category")
     *
     * @param CategoryRepository $repository
     *
     * @return Response
     */
    public function index()
    {
        $categories = $this->categoryService->callFindAll();

        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    /**
     * Adding new category
     * @Route("/new",name="category_new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * Edit category
     * @Route("/{id}/edit",name="category_edit", methods={"GET","PUT"})
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * Show posts in category
     * @Route("/{id}",name="category_posts", methods={"GET"}, requirements={"id":"[1-9]\d*"})
     *
     * @param Request $request
     *
     * @return Response
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
