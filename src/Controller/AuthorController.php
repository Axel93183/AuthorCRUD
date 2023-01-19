<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $request->isMethod('POST');
        $authorData = $request->request->all();

        $author = new Author();
        $author->setName($authorData['name']);
        $author->setDescription($authorData['description']);
        $author->setImageUrl($authorData['imageUrl']);
        
        $repository->save($author, true);


        return $this->render('author/newAuthor.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
        //$this->redirectToRoute('author_list');
         

    }

    // #[Route('/admin/auteurs', name: 'app_author_list')]

    // public function list(AuthorRepository $repository)
    // {
    //     $authors = $repository->findAll();

    //     return $this->render('author/list.html.twig', [
    //         'authors' => $authors
    //     ]);
    // }
}
