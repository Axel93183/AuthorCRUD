<?php

namespace App\Controller\Front;

use App\Form\SearchBookType;
use App\DTO\SearchBookCriteria;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/front/home', name: 'app_front_home_home')]
    public function home(BookRepository $repository): Response
    {
        //recuperer les 20 dernieres livres
        $books= $repository->findLastTwenty();

        //afficher la page de resultat
        return $this->render('front/home/home.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/search', name: 'app_front_home_search')]
    public function search(BookRepository $repository, Request $request): Response
    {
        //1. Création des critéres de recherche
        $criteria = new SearchBookCriteria();

        //2. Création du formulaire
        $form = $this->createForm(SearchBookType::class, $criteria);

        //3. Remplir le formulaire avec les critéres de recherche de l'utilisateur
        $form->handleRequest($request);

        //4. récupérer les livres selon les critéres donnés
        $books = $repository->findByCriteria($criteria);
        $nbResult = count($books);
        //5. affichage du twig
        return $this->render('front/home/search.html.twig', [
            'form' => $form->createView(),
            'books' => $books,
            'nbResult' => $nbResult,
           
        ]);

    }
}
