<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AdminCategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('admin/categories/nouvelle', name: 'app_category_create')]
    public function create(Request $request, CategoryRepository $repository): Response
    {
        {if ($request->isMethod('POST')) {
            $name=$request->request->get('name');
            $category = new Category();
            $category->setName($name);

            $repository->save($category, true);

            return $this->redirectToRoute('app_category_list');
        }
        }

        return $this->render('category/newCategory.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('admin/categories/list', name: 'app_category_list')]
    public function list(CategoryRepository $repository): Response
    {
        $categories= $repository->findAll();

        return $this->render('category/list.html.twig', [
            'Categories' => $categories,
        ]);
    }

    #[Route('admin/categories/{id}', name: 'app_category_update')]
    public function update(Request $request, int $id, CategoryRepository $repository): Response
    {
        $category = $repository->find($id);

        if ($request->isMethod('POST')) {
            $name=$request->request->get('name');

            $category->setName($name);

            $repository->save($category, true);

            return $this->redirectToRoute('app_category_list');
        }

        return $this->render('category/update.html.twig',['category'=> $category]);
    }

    #[Route('admin/categories/{id}/supprimer', name: 'app_category_delete')]

    public function delete(int $id, CategoryRepository $repository) : Response {

        $category = $repository->find($id);

        $repository->remove($category, true);
        
        return $this->redirectToRoute('app_category_list');
    }

    #[Route('/categories/creation', name: 'app_category_newCreate')]
    public function newCreate(CategoryRepository $repository, Request $request):Response
    {
        //création de l'objet PHP
        $category= new Category();

        //création du formulaire
        $form= $this->createForm(AdminCategoryType::class , $category);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie par le formulaire
            $validCategory = $form->getData();

            //enregistrer les donnée dans la bd
            $repository->save($validCategory, true);

            //redirection vers la liste des catégories
            return $this->redirectToRoute('app_category_list');
        }

        //récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('category/categoryCreateForm.html.twig' , [
            'form' => $formView,
        ]);


        /* ou bien ecrire les deux instrcution dans une seule:

         return $this->render('category/categoryCreateForm.html.twig' ,[
            'form' => $form->createView(),
        ]); 

        */
    }

    #[Route('/categories/updateForm/{id}', name: 'app_category_updateForm')]
    public function updateForm(int $id, CategoryRepository $repository, Request $request):Response {

        //Recupere les données de la catégorie avec son Id
        $category = $repository->find($id);

//------------------Puis ensuite même partie que la création------------------------//

        //création du formulaire avec $category en parametre ( cela pre-rempli le formulaire de modification avec la catégorie choisi à partir de son id de la ligne:128 ) 
        
        $form= $this->createForm(AdminCategoryType::class , $category);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie par le formulaire
            $validCategory = $form->getData();

            //enregistrer les donnée dans la bd
            $repository->save($validCategory, true);

            //redirection vers la liste des catégories
            return $this->redirectToRoute('app_category_list');
        }

         //récuperation de la view du formulaire
         $formView= $form->createView();

         //affichage dans le template
         return $this->render('category/categoryUpdateForm.html.twig' , [
             'form' => $formView,
             'category'=> $category
        ]);
 
    }
}