<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login'/* FIXME PARAMETRE POUR MODAL POPUP QUAND ENREGISTRE, ['registred' => true]*/);
        }
        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
}
