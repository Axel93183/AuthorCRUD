<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/inscription', name: 'app_security_registration')]
    public function registration(UserRepository $repository, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        //création du formulaire
        $form= $this->createForm(RegistrationType::class);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation des données du formulaire dans un objet book
            $user = $form->getData();

            //crypter le mot de passe
            $cryptedPass= $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($cryptedPass);

            //enregistrer les donnée dans la bd
            $repository->save($user, true);

            //redirection vers la page d'accueil
            return $this->redirectToRoute('app_front_home_home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_USER')]

    #[Route('/mon-profil', name: 'app_security_myProfile')]

    public function myProfile( UserRepository $repository, Request $request): Response
    {
        //Recupere les données de l'utilisateur connecté 
        $user= $this->getUser();

        //création du formulaire
        $form= $this->createForm(ProfilType::class, $user);

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 
 
        //si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){
 
            //recuperation des données du formulaire dans un objet user
            $validUser = $form->getData();
            //enregistrer les modifications dans la bd
            $repository->save($validUser, true);

            //redirection vers la page d'accueil
            return $this->redirectToRoute('app_front_home_home');
        }
          return $this->render('security/myProfile.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }



    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
