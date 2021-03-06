<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\User;
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
        return $this->createQueryBuilder('b')
            ->where('b.firstname LIKE :value')
            ->orWhere('b.lastname LIKE :value')
            ->setParameter('value', "%{$value}%")
            ->orderBy('b.firstname', 'ASC')
            ->orderBy('b.lastname', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNumber(string $value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.phone LIKE :value')
            ->setParameter('value', "%{$value}%")
            ->orderBy('b.firstname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByActif(bool $value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.actif = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByCreationDate(string $value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.creation_date < :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByUser(User $user, string $role = '')
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->andWhere('p.user = :user')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('user', $user)
            ->setParameter('role', "%{$role}%")
            ->getQuery()
            ->getResult()
        ;
    }
}
