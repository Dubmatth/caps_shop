<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $crypt = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($crypt);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success', 'Vous êtes désormais enregistré, vous pouvez vous connecter !'
            );
            return $this->redirectToRoute('home');
        }
        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(){
        return $this->render('security/admin.html.twig');
    }
    /**
     * @Route("/admin/products", name="adminProducts")
     */
    public function adminProducts(){
        $products = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->findAll();
        return $this->render('security/adminProducts.html.twig', ['products' => $products]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(){
        return $this->render('caps/index.html.twig');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
    }
}
