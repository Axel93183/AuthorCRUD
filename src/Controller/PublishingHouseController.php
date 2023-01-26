<?php

namespace App\Controller;

use App\Entity\PublishingHouse;
use App\Form\AdminPublishingHouseType;
use App\Repository\PublishingHouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublishingHouseController extends AbstractController
{
    #[Route('/publishing/house', name: 'app_publishing_house')]
    public function index(): Response
    {
        return $this->render('publishing_house/index.html.twig', [
            'controller_name' => 'PublishingHouseController',
        ]);
    }

    #[Route('/admin/publishing_house', name: 'app_publishinghouse_list')]
    public function list(PublishingHouseRepository $repository): Response
    {
        $PH= $repository->findAll();

        return $this->render('publishing_house/list.html.twig', [
            'PubHouse' => $PH,
        ]);
    }

    #[Route('/admin/publishing_house/new', name: 'app_publishinghouse_create')]
    public function create(PublishingHouseRepository $repository, Request $request):Response
    {
        
        $PH= new PublishingHouse();

        $form= $this->createForm(AdminPublishingHouseType::class , $PH);
      
        $form->handleRequest($request); 
      
        if($form->isSubmitted() && $form->isValid()){

            $validPH = $form->getData();

            $repository->save($validPH, true);

            return $this->redirectToRoute('app_publishinghouse_list');

        }

        $formView= $form->createView();

        return $this->render('publishing_house/create.html.twig' , [
            'form' => $formView,
        ]);
    }
}
