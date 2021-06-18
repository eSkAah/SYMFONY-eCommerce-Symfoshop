<?php

namespace App\Controller\Admin;

//namespace 
use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'), // Permet de récupérer le nom de l'objet écris sans espace ni rien pour l'URL
            ImageField::new('illustration') // Permet d'upload une image dans la BDD
            ->setBasePath('uploads/') // indique le dossier dans lequel on stock les images
            ->setUploadDir('public/uploads/')// indique la ROUTE complete de l'endroit ou on stock les images
            ->setUploadedFileNamePattern('[randomhash].[extension]')// hash le nom du fichier upload dans le BDD pour ne pas avoir : photo_famille.jpg par éxemple
            ->setRequired(false),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('category')

        ];


    }
    
}
