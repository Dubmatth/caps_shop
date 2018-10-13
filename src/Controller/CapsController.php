<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CapsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(){
        return $this->render('caps/index.html.twig');
    }
    /**
     * @Route("/products", name="products")
     */
    public function showProducts(){
        $products = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->findAll();
        return $this->render('caps/products.html.twig', ['products' => $products]);
    }
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/products/details-{id}", name="details")
     */
    public function details($id){
        $product = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->find($id);
        return $this->render('caps/details.html.twig', ['product' => $product]);
    }
    /**
     * @Route("products/edit-{id}", name="edit")
     */
    public function edit(){

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/products/add", name="add")
     */
    public function add(Request $request){
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            /*FIXME Ajout de l'utilisateur qui est log*/
            $product->setUser($this->getUser());
            $manager->persist($product);
            $manager->flush();
            $this->addFlash(
                'success', 'Vous avez ajouté un produit au catalogue !'
            );
            return $this->redirectToRoute('products');
        } else {
            return $this->render('caps/addProduct.html.twig', [
                'products' => $product,
                'form' => $form->createView()
            ]);
        }
    }



    /**
     * @Route("/cgv", name="cgv")
     */
    public function cgv(){
        return $this->render('caps/cgv.html.twig');
    }
    /**
     * @Route("/legals", name="legalsMentions")
     */
    public function legalsMentions(){
        return $this->render('caps/legalsMentions.html.twig');
    }
    /**
     * @Route("/about", name="about")
     */
    public function about(){
        return $this->render('caps/about.html.twig');
    }

    
}
