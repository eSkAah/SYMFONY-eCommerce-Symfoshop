<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User; // Il faut importer l'Entity User pour pouvoir intéragir avec 
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request; // Request HttpFoundation pour récupérer la requete
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // Appel du component qui permet de crypter les mots de passe

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response // Je récupère la requete envoyer de la page Register
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // Ecoute la requête qui entre, voir si j'ai un POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData(); // Stockage dans $user les Data du formulaire

            $password = $encoder->encodePassword($user, $user->getPassword()); // cryptage du mot de passe

            $user->setPassword($password); // On set le password de $user, avec le nouveau mot de passe crypté

            //dd($password); // var_dump  permet d'analyser ce qui se trouve dans la Variable $user

            // Première méthode pour utiliser Doctrine
            //$doctrine = $this->getDoctrine()->getManager(); // Va chercher Dotrine, et va chercher le getManager on a doctrine dans la variable

            $this->entityManager->persist($user);// Permet de figer la data d'un objet, donc la notre entité User  = ->prepare();
            $this->entityManager->flush();// prend la data et enregistre en base de données = ->execute();

            
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
