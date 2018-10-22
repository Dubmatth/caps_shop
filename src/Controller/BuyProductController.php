<?php

namespace App\Controller;

use App\Entity\Product;
use Cart\CartItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Cart\Cart;
use Cart\Storage\SessionStore;


class BuyProductController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/panier", name="panier")
     */
    public function index(){
        $session = new Session();
        $panier = $session->get('panier');

        //Verifie si le panier contient quelque chose pour affichage btn retour produit//continuer achat
        if (isset($panier)){
            $panier = $session->get('panier')->toArray()['items'];
            if (count($panier) < 1){
                $session->remove('panier');
            }
        }
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
       if (!$session->get('panier')){
           $id = 'cart-01';
           $cartSessionStore = new SessionStore();
           $session->set('panier', new Cart($id, $cartSessionStore));
       }
        $panier = $session->get('panier');
        $item = new CartItem();
        $item->name = $product->getTitle();
        $item->image = $product->getImage();
        $item->price = $product->getPrice();
        $item->tax = $item->price * 0.21;
        $totalPrice = $item->getTotalPrice();
        $panier->add($item);
        $this->addFlash(
            'success', 'Vous avez ajouté un article dans votre panier !'
        );
        return $this->render('buy_product/buyProduct.html.twig', [
            'products' => $product,
            'totalPrice' => $totalPrice,
            'panier' => $panier
        ]);

    }

    /**
     * @Route("/panier/delete/{id}", name="panierDel")
     * @param SessionInterface $session
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteItem(SessionInterface $session, $id)
    {
        $panier = $session->get('panier')->toArray()['items'];
        foreach ($panier as $item)
        {
            if  ($item['id'] == $id){
                if ($item['data']['quantity'] == 1){
                    $session->get('panier')->remove($id);
                }else {
                    $session->get('panier')->update($id, 'quantity', $item['data']['quantity'] -= 1 );
                }
            }
        }

        $this->addFlash(
            'danger', 'Vous avez enlevé un article de votre panier !'
        );
        return $this->redirectToRoute('panier');
    }
    /**
     * @Route("/clearPanier", name="clearPanier")
     */
    public function clearPanier(){
        $session = new Session();
        $panier = $session->get('panier');

        // Test si panier à déjà été vidé une fois
        if (!$panier == null){
            $this->addFlash(
                'danger', 'Vous avez vidé votre panier !'
            );
        } else {
            $this->addFlash(
                'danger', 'Votre panier à déjà été vidé !'
            );
        }

        $session->remove('panier');

        return $this->redirectToRoute('panier');
    }
}
