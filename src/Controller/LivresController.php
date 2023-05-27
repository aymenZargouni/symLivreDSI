<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivresController extends AbstractController
{   //paramconverter
    #[Route('/livres/{id}', name: 'app_livres_id')]
    public function find(Livres $livre): Response
    {
        dd($livre);


    }
    #[Route('/admin/livres', name: 'app_livres')]
    #[IsGranted('ROLE_ADMIN')]
    public function findAll(LivresRepository $rep, PaginatorInterface $paginator, Request $request): Response
    {
         $livres=$rep->findAll();

         $pagination = $paginator->paginate(
            $livres, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('livres/findAll.html.twig',['livres'=>$pagination
        ]);
   }
   #[Route('admin/livres/add', name: 'admin_livres_add')]
   #[IsGranted('ROLE_ADMIN')]
   public function add1(ManagerRegistry $doctrine): Response
   {    $dateedition=new \DateTime('2022-01-01');
       $livre = new Livres();
       $livre->setLibelle('Réseau')
             ->setPrix(120)
             ->setDescription('bla bla bla bla ')
             ->setImage('https://via.placeholder.com/300')
             ->setEditeur('Eyrolles')
             ->setDateEdition($dateedition);

       $em=$doctrine->getManager();
       $em->persist($livre);
       $em->flush();
       dd($livre);    }

   #[Route('admin/livres/update/{id}', name: 'app_livres_id')]
   #[IsGranted('ROLE_ADMIN')]
   public function update(Livres $livre,ManagerRegistry $doctrine): Response
   {
       $livre->setPrix(110);
       $em=$doctrine->getManager();
       $em->flush();
       dd($livre);
   }
   #[Route('admin/livres/delete/{id}', name: 'admin_livres_id_delete')]
   #[IsGranted('ROLE_ADMIN')]
   public function delete(Livres $livre,ManagerRegistry $doctrine): Response
   {
       $em=$doctrine->getManager();
       $em->remove($livre);
       $em->flush();
       //dd($livre);
       return $this->redirectToRoute('app_livres');
   }
   #[Route('admin/livres/add2', name: 'admin_livres_add2')]
   #[IsGranted('ROLE_ADMIN')]
   public function add2(ManagerRegistry $doctrine , Request $request): Response
   {
   $livre = new Livres();
   $form = $this->createForm(LivreType::class,$livre);
   $form->handleRequest($request);
   
   if ($form->isSubmitted() && $form->isValid()) { 
       $livre = $form->getData();
       $em = $doctrine->getManager();
       $em->persist($livre);
       $em->flush();

      // return new Response("Le livre a été ajoutée dans la base");
      return $this->redirectToRoute('app_livres');

    }

   return $this->render('livres/add.html.twig',['f'=>$form
    
   ]);
}
}
