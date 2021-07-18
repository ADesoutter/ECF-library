<?php

namespace App\Repository;

use App\Entity\Borrower;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Borrower|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrower|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrower[]    findAll()
 * @method Borrower[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrower::class);
    }

    /**
     * @return Borrower[] Returns an array of Borrower objects
     */
    
    public function findByFirstnameOrLastname($value)
    {
        // Récupération d'un query builder.
        $qb = $this->createQueryBuilder('s');

        // Au lieu de fournir du code DQL à la fonction where(),
        // on lui fournit des des objets de type Expr (experssion).
        // Les objets de type Expr peuvent être structurés en forme
        // d'arbre pour créer des conditions complexes.
        // Ici nous créons la condition :
        // WHERE (s.firstname LIKE :value OR s.lastname LIKE :value)
        return $qb->where($qb->expr()->orX(
                $qb->expr()->like('s.firstname', ':value'),
                $qb->expr()->like('s.lastname', ':value')
            ))
            // Affactation d'une valeur à la variable :value.
            // Le symbole % est joker qui veut dire
            // « match toutes les chaînes de caractères ».
            ->setParameter('value', "%{$value}%")
            // Tri par firstname en ordre croissant (a, b, c, ...).
            ->orderBy('s.firstname', 'ASC')
            // En cas de firstname identiqu, on ajoute un tri par
            // lastname en ordre croissant (a, b, c, ...).
            ->orderBy('s.lastname', 'ASC')
            // Récupération d'une requête qui n'attend qu'à être exécutée.
            ->getQuery()
            // Exécution de la requête.
            // Récupération d'un tableau de résultat.
            // Ce tableau peut contenir, zéro, un ou plusieurs lignes.
            ->getResult()
        ;

        /*
        // Version plus simple du code mais qui n'est adpatée que
        // si on n'enchaîne des conditions de type OR ou AND et
        // pas des conditions mélangeant des OR et des AND.
        // WHERE s.firstname LIKE :value OR s.lastname LIKE :value
        return $this->createQueryBuilder('s')
            ->where('s.firstname LIKE :value')
            ->orWhere('s.lastname LIKE :value')
            ->setParameter('value', "%{$value}%")
            ->orderBy('s.firstname', 'ASC')
            ->orderBy('s.lastname', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
        */
    }

    // /**
    //  * @return Borrower[] Returns an array of Borrower objects
    //  */
    /*
        public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneBySomeField($value): ?Borrower
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
