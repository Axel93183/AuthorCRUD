<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AdminAuthorType;
use App\Form\SearchAuthorType;
use App\DTO\SearchAuthorCriteria;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//Permet de contrôler l'accès et le restreindre à l'administrateur / redirige vers la page de login
#[IsGranted('ROLE_ADMIN')]

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/admin/auteurs/nouveau', name: 'app_author_create')]
    public function create(Request $request, AuthorRepository $repository) : Response
    {
    if ($request->isMethod('POST')) {
    $name=$request->request->get('name');
    $description= $request->request->get('description');
    $imageUrl= $request->request->get('imageUrl');


    $author = new Author();
    $author->setName($name);
    $author->setDescription($description);
    $author->setImageUrl($imageUrl);

    $repository->save($author, true);

    return $this->redirectToRoute('app_author_list');
}
    return $this->render('author/newAuthor.html.twig');
}

    #[Route('/admin/auteurs', name: 'app_author_list')]

    public function list(AuthorRepository $repository, Request $request)
    {
         //1. Création des critéres de recherche
         $criteria = new SearchAuthorCriteria();

         //2. Création du formulaire
         $form = $this->createForm(SearchAuthorType::class, $criteria);
 
         //3. Remplir le formulaire avec les critéres de recherche de l'utilisateur
         $form->handleRequest($request);

          //4. récupérer les livres selon les critéres donnés
         $authors = $repository->findAuthorByCriteria($criteria);

        return $this->render('author/list.html.twig', [
            'form' => $form->createView(),
            'authors' => $authors
        ]);
    }

    #[Route('/admin/auteurs/{id}', name: 'app_author_update')]

    public function update(int $id, AuthorRepository $repository, Request $request){
        
        $author=$repository->find($id);
        
        if ($request->isMethod('POST')) {
      
            $name=$request->request->get('name');
            $description= $request->request->get('description');
            $imageUrl= $request->request->get('imageUrl');
                    
            $author->setName($name);
            $author->setDescription($description);
            $author->setImageUrl($imageUrl);
        
            $repository->save($author, true);
        
            return $this->redirectToRoute('app_author_list');
        }


        return $this->render('author/update.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/admin/auteurs/{id}/supprimer', name: 'app_author_remove')]
    public function remove(int $id, AuthorRepository $repository): Response
    {
        //récuperer l'auteur depuis la base de données
        $author= $repository->find($id);

        //je supprime l'auteur
        $repository->remove($author, true);

        //redirection vers la liste des auteurs
        return $this->redirectToRoute('app_author_list');
    }

    #[Route('/auteurs/creation', name: 'app_author_newCreate')]
    public function newCreate(AuthorRepository $repository, Request $request):Response
    {
        //création de l'objet PHP
        $author= new Author();

        //création du formulaire
        $form= $this->createForm(AdminAuthorType::class , $author);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie pas le formulaire
            $validAuthor = $form->getData();

            //enregistrer les donnée dans la bd
            $repository->save($validAuthor, true);

            //redirection vers la liste des auteurs
            return $this->redirectToRoute('app_author_list');
        }

        //récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('author/authorCreateForm.html.twig' , [
            'form' => $formView,
        ]);


        /* ou bien ecrire les deux instrcution dans une seule:

         return $this->render('pizza/createForm.html.twig' ,[
            'form' => $form->createView(),
        ]); 

        */
    }
    #[Route('/auteurs/updateForm/{id}', name: 'app_author_updateForm')]
    public function updateForm(int $id, AuthorRepository $repository, Request $request):Response {

        //Recupere les données de l'auteur avec son Id
        $author = $repository->find($id);

//------------------Puis ensuite même partie que la création------------------------//

        //création du formulaire avec $author en parametre ( cela pre-rempli le formulaire de modification avec l'auteur choisi a partir de son id de la ligne:141 ) 
        
        $form= $this->createForm(AdminAuthorType::class , $author);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie pas le formulaire
            $validAuthor = $form->getData();

            //enregistrer les donnée dans la bd
            $repository->save($validAuthor, true);

            //redirection vers la liste des auteurs
            return $this->redirectToRoute('app_author_list');
        }

         //récuperation de la view du formulaire
         $formView= $form->createView();

         //affichage dans le template
         return $this->render('author/updateForm.html.twig' , [
             'form' => $formView,
             'author'=> $author
        ]);
 
    }

}
