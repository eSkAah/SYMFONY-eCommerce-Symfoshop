<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * Requête qui permet de récupérer les produits en fonction de la recherche User.
     * @return Product[]
     */
    public function findWithSearch(Search $search){
        $query = $this   // Définition d'une variable $query,  dans cette quesry on va utiliser plusieurs méthodes
            ->createQueryBuilder('p')   // Création d'une requête, et tu fais le Mapping avec la table PRODUCTS DONC  'p'
            ->select('c', 'p')  // Selectionne la CATEGORY dans l'entité PRODUCTS
            ->join('p.category', 'c'); // Créer une jointure entre les CATEGORY PRODUIT et la table CATEGORIES

        if(!empty($search->categories)) {  // SI et seulement si la recherche n'est pas vide alors : 
            $query = $query
                ->andWhere('c.id IN (:categories)')  // j'ai besoin que les ID et category soient dans la liste de categories que je t'envoi
                ->setParameter('categories', $search->categories); // je te définis que CATEGORIES =   ce qui se trouve dans l'objet  $search=> categories
        }

        return $query->getQuery()->getResult();


        if(!empty($search->string)) {
            $query = $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', "%$search->string%");
        }

        
    }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
