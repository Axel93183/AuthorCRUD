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

    public function list(AuthorRepository $repository)
    {
        $authors = $repository->findAll();

        return $this->render('author/list.html.twig', [
            'authors' => $authors
        ]);
    }
}
