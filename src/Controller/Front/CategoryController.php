<?php

namespace App\Controller\Front;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/front/category', name: 'app_front_category')]
    public function index(): Response
    {
        return $this->render('front/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/categorie/{id}', name: 'app_front_category_display')]
    public function display(int $id, BookRepository $repository, CategoryRepository $catrepo): Response
    {
        //recupere la liste des livres de la catégorie ciblé trié par prix décroissant
        $books= $repository->findBookByCategory($id);
        $category= $catrepo->find($id);

        return $this->render('front/category/display.html.twig', [
            'books' => $books,
            'category' => $category,
        ]);
    }
}
