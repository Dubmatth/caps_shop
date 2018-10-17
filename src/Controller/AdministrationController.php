<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\AdminEditUserType;
use App\Form\ProductType;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration", name="administration")
     */
    public function index()
    {
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard(){
        return $this->render('security/dashboard.html.twig');
    }
    /**
     * @Route("/admin/products", name="adminProduct")
     */
    public function adminProducts(){
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        return $this->render('security/adminProduct.html.twig', ['products' => $products]);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("admin/products/edit-{id}", name="adminProductEdit")
     */
    public function editProductAdmin(Request $request, Product $product){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_USER');
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()
                ->getManager();
            $em->flush();
            $this->addFlash(
                'success', 'Vous avez modifié ce produit avec succès !'
            );
            return $this->redirectToRoute('adminProduct');
        } else {
            return $this->render('security/adminProductEdit.html.twig', [
                'product' => $product,
                'form' => $form->createView()
            ]);
        }
    }
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/products/delete-{id}", name="adminProductDelete")
     */
    public function deleteProductAdmin($id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $em->remove($product);
        $em->flush();
        $this->addFlash(
            'danger', 'Vous avez supprimé cet article avec succès !'
        );
        return $this->redirectToRoute('adminProduct');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/users", name="adminUser")
     */
    public function adminUser(){
        $users = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findAll();
        return $this->render('security/adminUser.html.twig', [
            'users' => $users
        ]);

    }

    /**
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/users/edit-{id}", name="adminUserEdit")
     */
    public function editUserAdmin(Request $request, User $user, UserPasswordEncoderInterface $encoder){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(AdminEditUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $crypt = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($crypt);
            $em->flush();
            $this->addFlash(
                'success', 'Vous avez modifié cet utilisateur avec succès !'
            );
            return $this->redirectToRoute('admin');
        } else {
            return $this->render('security/adminUserEdit.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/users/delete-{id}", name="adminUserDelete")
     */
    public function deleteUserAdmin($id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        $this->addFlash(
            'danger', 'Vous avez supprimé cet utilisateur avec succès !'
        );
        return $this->redirectToRoute('adminUser');
    }


}
