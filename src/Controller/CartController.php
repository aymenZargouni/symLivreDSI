<?php

namespace App\Controller;

use App\Repository\LivresRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session,LivresRepository $livreRepository): Response
    {
        $total = 0;

        $panier = $session->get('cart',[]);

        $panierData = [];

        foreach($panier as $id => $qte){
            $panierData[] = [
                'livre' => $livreRepository->find($id),
                'qte' => $qte
            ];
        
            foreach($panierData as $item){
                $total += $item['livre']->getPrix() * $item['qte'];
            }
        } 
            return $this->render('cart/index.html.twig' , [ 'items' => $panierData , 'total' =>$total]);
    }
    #[Route('/cart/add/{id}', name: 'add_cart')]
    public function addLivreToCart($id , SessionInterface $session): Response
    {
        $cart = $session->get('cart',[]);
        if(!array_key_exists($id,$cart)){
            $cart[$id] = 0;
        }
            $cart[$id]++;
            $session->set('cart',$cart);
        
            return $this->redirectToRoute('app_cart');
        }

    #[Route('/cart/remove/{id}', name: 'remove_cart')]
    public function removeLivreFromCart($id,SessionInterface $session): Response
    {
        $cart = $session->get('cart',[]);
        if ( array_key_exists($id,$cart)){
                unset($cart[$id]);
            }
            $session->set('cart',$cart);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/add/{id}', name: 'add_qte')]
    public function addQte($id , SessionInterface $session): Response
    {
        $cart = $session->get('cart',[]);
            $cart[$id]++;
            $session->set('cart',$cart);
        
            return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'remove_qte')]
    public function removeQte($id,SessionInterface $session): Response
    {
        $cart = $session->get('cart',[]);
            $cart[$id]--;
            if($cart[$id] <= 0){
                unset($cart[$id]);
            }
            $session->set('cart',$cart);

        return $this->redirectToRoute('app_cart');
    }
        
    

 /*   #[Route('/cart', name: 'show_cart')]
    public function showCart(): Response
    {
        $cart = $session->get('cart',$cart);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }
    */
}
