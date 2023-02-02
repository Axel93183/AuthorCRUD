<?php

namespace App\Controller;

use App\Entity\PublishingHouse;
use App\Form\AdminPublishingHouseType;
use App\Repository\PublishingHouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//Permet de contrôler l'accès et le restreindre à l'administrateur / redirige vers la page de login
#[IsGranted('ROLE_ADMIN')]

class PublishingHouseController extends AbstractController
{
    #[Route('/publishing/house', name: 'app_publishing_house')]
    public function index(): Response
    {
        return $this->render('publishing_house/index.html.twig', [
            'controller_name' => 'PublishingHouseController',
        ]);
    }

    #[Route('/admin/publishing_house', name: 'app_publishing_house_list')]
    public function list(PublishingHouseRepository $repository): Response
    {
        $PH= $repository->findAll();

        return $this->render('publishing_house/list.html.twig', [
            'PubHouse' => $PH,
        ]);
    }

    #[Route('/admin/publishing_house/new', name: 'app_publishing_house_create')]
    public function create(PublishingHouseRepository $repository, Request $request):Response
    {
        
        $PHouse= new PublishingHouse();

        $form= $this->createForm(AdminPublishingHouseType::class , $PHouse);
      
        $form->handleRequest($request); 
      
        if($form->isSubmitted() && $form->isValid()){

            $validPHouse = $form->getData();

            $repository->save($validPHouse, true);

            return $this->redirectToRoute('app_publishing_house_list');

        }

        $formView= $form->createView();

        return $this->render('publishing_house/create.html.twig' , [
            'form' => $formView,
        ]);
    }

    #[Route('/admin/publishing_house/update/{id}', name: 'app_publishing_house_update')]
    public function update(int $id, PublishingHouseRepository $repository, Request $request):Response {
       
        $PHouse = $repository->find($id);

        $form= $this->createForm(AdminPublishingHouseType::class , $PHouse);
      
        $form->handleRequest($request); 
      
        if($form->isSubmitted() && $form->isValid()){

            $validPHouse = $form->getData();

            $repository->save($validPHouse, true);

            return $this->redirectToRoute('app_publishing_house_list');

        }

        $formView= $form->createView();

        return $this->render('publishing_house/update.html.twig' , [
            'form' => $formView,
            'PubHouse' => $PHouse,
        ]);
    }

    #[Route('/admin/publishing_house/remove/{id}', name: 'app_publishing_house_remove')]
    public function remove(int $id, PublishingHouseRepository $repository): Response
    {
        $PHouse= $repository->find($id);
       
        $repository->remove($PHouse, true);
        
        return $this->redirectToRoute('app_publishing_house_list');
    }

}
