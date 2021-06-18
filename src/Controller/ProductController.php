<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Products;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entitymanager){
        $this->entityManager = $entitymanager;
        
    }


    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request) // traiter le formulaire
    {

        $products = $this->entityManager->getRepository(Products::class)->findAll();

        $search = new Search(); // initialiser class Search
        $form = $this->createForm(SearchType::class, $search ); 

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Products::class)->findWithSearch($search);
           
        }
        
        return $this->render('product/index.html.twig', [
            'products'=>$products,
            'form'=> $form->createView()
        ]);
    }

    
    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response
    {

        $product = $this->entityManager->getRepository(Products::class)->findOneBySlug($slug);

        if(!$product){
            return $this->redirectToRoute('products'); // Si pas de Slug, tu redirige vers la page produits
        }

        return $this->render('product/show.html.twig', [
            'product'=>$product
        ]);
    }


}
