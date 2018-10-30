<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('security/admin.html.twig');
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(){
        return $this->render('caps/login.html.twig');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
        $this->addFlash(
            'danger', 'Vous êtes déconnecté !'
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/paiement", name="payments")
     */
    public function payment(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_USER');
        return $this->render('security/payments.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/paiement-verifie", name="verifiedPayment")
     */
    public function verifiedPayment(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_USER');
        $session = new Session();
        $panier = $session->get('panier');
        if (!empty($panier)){
            $panierTotal = $session->get('panier')->total() * 100;

            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey("sk_test_nZ9Y47e2xHwD5iAlmAen1Pmz");
            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $token = $request->request->get('stripeToken');
            $charge = \Stripe\Charge::create([
                'amount' => $panierTotal,
                'currency' => 'eur',
                'description' => 'Example charge',
                'source' => $token,
            ]);
            $session->remove('panier');
            $this->addFlash('success', 'Merci, le paiement à bien été effectué, vous recevrez un email de confirmation dans les prochaines minutes ... ');

        } else {
            $this->addFlash('danger', 'Aucun panier à valider');

        }

        return $this->render('buy_product/buyProduct.html.twig');

    }
}
