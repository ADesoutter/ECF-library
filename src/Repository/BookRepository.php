<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Borrower;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */

    public function findByTitle(string $value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.title LIKE :value')
            ->setParameter('value', "%{$value}%")
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Book[] Returns an array of Book objects
    */

    public function findByAuhtor(int $id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.author = :id')
            ->setParameter('id', $id)
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByGenre($genre)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.genre', 'g')
            ->andWhere('g.name LIKE :genre')
            ->setParameter('genre', "%{$genre}%")
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRole(string $role)
     {
         return $this->createQueryBuilder('u')
             ->andWhere('u.roles LIKE :role')
             ->setParameter('role', "%{$role}%")
             ->orderBy('u.email', 'ASC')
             ->getQuery()
             ->getResult()
        ;
    }


    public function findByTitleOrAuthor($value)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.author', 'a')
            ->andWhere('a.lastname LIKE :author')
            ->orWhere('b.title LIKE :title')
            ->orWhere('a.firstname LIKE :author')
            ->setParameter('title', "%{$value}%")
            ->setParameter('author', "%{$value}%")
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
