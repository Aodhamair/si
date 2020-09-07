<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;
use App\Form\PasswordTypeForm;
use Doctrine\Common\Annotations\Annotation;
use Symfony\Component\HttpFoundation\Response;




class UsersController extends AbstractController
{

    /**

     *
     *
     * @Route("/changepassword", name="change_password", methods={"GET","PUT"})
     */
    public function changePassword(UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user = $this->getUser();
        dump($user);
        $form = $this->createForm(PasswordTypeForm::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);
        dump($user);


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $userRepository->save($user);
            dump($user);
//            $this->addFlash('success', 'message_edited_successfully');
            return $this->redirectToRoute("posts");
        }
        return $this->render('user/password.html.twig', ['user'=>$user,'form'=>$form->createView()]);

    }

}