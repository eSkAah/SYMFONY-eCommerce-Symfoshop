<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/modifier-mdp", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)//Définition de la requete entrante, et injection de dépendance HttpFoundation 
    {

        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);


        $form->handleRequest($request);// On écoute la requête qui entre après la validation sur le Submit

        if($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();// récupère la Data name=old_password du formulaire
            if($encoder->isPasswordValid($user, $old_pwd)){// Si le mot de pass encodé est égale au mot de passe entré par l'user comme PDO
                $new_pwd = $form->get('new_password')->getData();// Récupere le nouveau passsword avec le getData et stock dans $new_pwd
                $password = $encoder->encodePassword($user, $new_pwd);// Tu crypte le nouveau mot de passe

                $user->setPassword($password);// set le nouveau password crypté de $user avec le new_pwd

                // Il ne reste plus qu'a dire a Doctrine de mettre à jour en BDD

                $this->entityManager->persist($user);// Permet de figer la data d'un objet, donc la notre entité User  = ->prepare();
                $this->entityManager->flush();// prend la data et enregistre en base de données = ->execute();
                $notification = "Votre mot de passe à bien été modifié";
            }else{
                $notification = "Votre mot de passe n'a pas été modifié";
            } 
            



        }

        return $this->render('account/password.html.twig', [
            'form'=>$form->createView(),
            'notification'=>$notification
        ]);
        
    }
}
