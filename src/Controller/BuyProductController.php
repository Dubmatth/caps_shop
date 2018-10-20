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
       if (!$session->get('panier')){
           $id = 'cart-01';
           $cartSessionStore = new SessionStore();
           $session->set('panier', new Cart($id, $cartSessionStore));
       }
        $panier = $session->get('panier');
        $item = new CartItem();
        $item->name = $product->getTitle();
        $item->price = $product->getPrice();
        $item->tax = $item->price * 0.21;
        $totalPrice = $item->getTotalPrice();
        $panier->add($item);
        return $this->render('buy_product/buyProduct.html.twig', [
            'products' => $product,
            'totalPrice' => $totalPrice
        ]);

    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     * @param SessionInterface $session
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteItem(SessionInterface $session, $id)
    {
        $panier = $session->get('panier')->toArray()['items'];
        foreach ($panier as $item)
        {
            if  ($item['id'] == $id)
            {
                if ($item['data']['quantity'] == 1)
                {
                    $session->get('panier')->remove($id);
                }
                else
                {
                    $session->get('panier')->update($id, 'quantity', $item['data']['quantity']-=1 );
                }
            }
        }
        return $this->redirectToRoute('cart');
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
