<?php

namespace App\Controller;

use App\Entity\Category;
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
}