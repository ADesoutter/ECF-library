<?php

namespace App\Repository;

use App\Entity\User;


Trait ProfileRepositoryTrait
{
    /**
     * @param $role string optional 
     * @return App\Entity\Book|App\Entity\Author|App\Entity\Borrower
     */
     public function findOneByUser(User $user, string $role = '')
    {
        
         return $this->createQueryBuilder('p')
            
             ->innerJoin('p.user', 'u')
            ->andWhere('p.user = :user')
             ->andWhere('u.roles LIKE :role')
             ->setParameter('user', $user)
             ->setParameter('role', "%{$role}%")
            ->getQuery()
            ->getOneOrNullResult()
         ;
     }
 }