<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\BorrowerRepository;
use App\Repository\BorrowingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(
        BookRepository $bookRepository,
        BorrowerRepository $borrowerRepository,
        BorrowingRepository $borrowingRepository,
        UserRepository $userRepository): Response
    {
        // Les Utilisateurs
        // Récupération de la liste complète des users.
        $users = $userRepository->findAll();
        dump($users);

        // Récupération de l'user dont l'id est 1.
        $user = $userRepository->find(1);
        dump($users);

        // Récupération des données de l'adresse email.
        $user->$userRepository->find('foo@example.com');
        dump($users);

        // Récupération des données des users dont le role est ROLE_EMPRUNTEUR.
        $user->$userRepository->findByRole('ROLE_EMPRUNTEUR');
        dump($users);


        // Les livres
        // Récupération de la liste complète des livres.
        $books = $bookRepository->findAll();
        dump($books);

        // Récupération du livre dont l'id est 1.
        $book = $bookRepository->find(1);
        dump($books);

        // Récupération des livres dont le titre contient le mot clé: "lorem".
        $books = $bookRepository->findOneBy(['title' => 'lorem']);
        dump($books);

        //Récupération de la liste des livres dont l'id de l'auteur est 2.


        //Récupération de la liste des livres dont le genre contient le mot clé 'roman'.
        $books = $bookRepository->findOneBy(['genre' => 'roman']);
        dump($books);

        exit();
    }
}
