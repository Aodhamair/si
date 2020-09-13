<?php

namespace App\Controller;

use App\Form\EmailTypeForm;
use App\Form\PasswordTypeForm;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * UsersController constructor.
     *
     * @param \App\Service\UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/changepassword", name="change_password", methods={"GET","PUT"})
     */
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordTypeForm::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash('success', 'message_edited_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render('user/password.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @Route("/changemail", name="change_email", methods={"GET","PUT"})
     */
    public function changeEmail(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EmailTypeForm::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->saveEmail($user);
            $this->addFlash('success', 'message_edited_successfully');

            return $this->redirectToRoute('posts');
        }

        return $this->render('user/email.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }
}
