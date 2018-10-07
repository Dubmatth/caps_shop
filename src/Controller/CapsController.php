<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('caps/products.html.twig');
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
