<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;
use App\Form\PasswordTypeForm;
use Doctrine\Common\Annotations\Annotation;




class UsersController extends AbstractController
{

    /**

     *
     *
     * @Route("/changepassword", name="change_password")
     */
    public function changePassword(UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordTypeForm::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $form->getPassword()));
            $userRepository->save($user);
            $this->addFlash('success', 'message_edited_successfully');
            return $this->redirectToRoute("posts");
        }
        return $this->render('user/password.html.twig', ['form'=>$form->createView()]);

    }

}