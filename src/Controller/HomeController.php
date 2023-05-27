<?php

namespace App\Controller;

use App\Repository\LivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function findAll(LivresRepository $rep, Request $request): Response
    {
         $livres=$rep->findAll();

        return $this->render('home/findAll.html.twig',['livres'=>$livres
        ]);
   }
}
