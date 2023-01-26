<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\AdminBookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/admin/livre/nouveau', name: 'app_book_create')]
    public function create(BookRepository $repository, Request $request):Response
    {
        //création de l'objet PHP
        $book= new Book();

        //création du formulaire
        $form= $this->createForm(AdminBookType::class , $book);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation des données du formulaire dans un objet book
            $validBook = $form->getData();

            //enregistrer les donnée dans la bd
            $repository->save($validBook, true);

            //redirection vers la liste des livres
            return $this->redirectToRoute('app_book_list');
        }

        //récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('book/bookCreateForm.html.twig' , [
            'form' => $formView,
        ]);
    }

    #[Route('admin/livres', name: 'app_book_list')]
    public function list(BookRepository $repository): Response
    {
        $books= $repository->findAll();

        return $this->render('book/list.html.twig', [
            'Books' => $books,
        ]);
    }

    #[Route('/admin/livres/update/{id}', name: 'app_book_update')]
    public function update(int $id, BookRepository $repository, Request $request):Response {

        //Recupere les données du livre avec son Id
        $book = $repository->find($id);

//------------------Puis ensuite même partie que la création------------------------//

        //création du formulaire avec $book en parametre ( cela pre-rempli le formulaire de modification avec l'auteur choisi a partir de son id de la ligne:71 ) 
        
        $form= $this->createForm(AdminBookType::class , $book);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie pas le formulaire
            $validBook = $form->getData();

            //enregistrer les donnée dans la bd
            $repository->save($validBook, true);

            //redirection vers la liste des livres
            return $this->redirectToRoute('app_book_list');
        }

         //récuperation de la view du formulaire
         $formView= $form->createView();

         //affichage dans le template
         return $this->render('book/update.html.twig' , [
             'form' => $formView,
             'book'=> $book
        ]);
 
    }

    #[Route('/admin/livres/remove/{id}', name: 'app_book_remove')]
    public function remove(int $id, BookRepository $repository): Response
    {
        //récuperer le livre depuis la base de données
        $book= $repository->find($id);

        //je supprime l'auteur
        $repository->remove($book, true);

        //redirection vers la liste des auteurs
        return $this->redirectToRoute('app_book_list');
    }

}
