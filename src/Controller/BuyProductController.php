<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class BuyProductController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/panier", name="panier")
     */
    public function index(){
        $session = new Session();
        /*$session->remove('panier');*/
        $session->get('panier');

        return $this->render('buy_product/buyProduct.html.twig');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/addPanier-{id}", name="addPanier")
     */
    public function addBuyProduct($id){
        $session = new Session();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if(!$session->get('panier')) $session->set('panier', array());
        $panier = $session->get('panier');

        $panier[$id] = $product;

        $session->set('panier', $panier);
        $this->addFlash(
            'success', 'Vous avez ajouté ce produit avec succès !'
        );
        return $this->render('buy_product/buyProduct.html.twig');
    }

    /**
     * @Route("/clearPanier", name="clearPanier")
     */
    public function clearPanier(){
        $session = new Session();
        $session->remove('panier');
        $this->addFlash(
            'danger', 'Vous avez vider votre panier !'
        );
        return $this->render('buy_product/buyProduct.html.twig');
    }
}
