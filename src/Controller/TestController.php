<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrowing;
use App\Entity\Genre;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\BorrowerRepository;
use App\Repository\BorrowingRepository;
use App\Repository\GenreRepository;
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
        AuthorRepository $authorRepository,
        BookRepository $bookRepository,
        BorrowerRepository $borrowerRepository,
        BorrowingRepository $borrowingRepository,
        GenreRepository $genreRepository,
        UserRepository $userRepository): Response
    {
        // Récupération de l'entity manager.
        $entityManager = $this->getDoctrine()->getManager();

        

        // Les Utilisateurs
        // Récupération de la liste complète des users.
        $users = $userRepository->findAll();
        dump($users);

        // Récupération de l'user dont l'id est 1.
        $user = $userRepository->find(1);
        dump($user);

        // Récupération des données de l'adresse email 'foo@example.com'.
        $foo = 'foo@example.com';
        $user = $userRepository->findOneByEmail($foo);
        dump($user);

        // Récupération des données des users dont le role est ROLE_EMPRUNTEUR.
        $user = $userRepository->findByRole('ROLE_EMPRUNTEUR');
        dump($user);



        // Les livres
        // Récupération de la liste complète des livres.
        $books = $bookRepository->findAll();
        dump($books);

        // Récupération du livre dont l'id est 1.
        $book = $bookRepository->find(1);
        dump($book);

        // Récupération des livres dont le titre contient le mot clé: "lorem".
        $title = 'lorem';
        $books = $bookRepository->findByTitle($title);
        dump($books);

        //Récupération de la liste des livres dont l'id de l'auteur est 2.
        $author = 2;
        $books = $bookRepository->findByAuhtor($author);
        dump($books);

        //Récupération de la liste des livres dont le genre contient le mot clé 'roman'.
        $genre = 'roman';
        $books = $bookRepository->findByGenre($genre);
        dump($books);

//         // Requête de création
//         //Ajouter un nouveau livre
//         // - titre : Totum autem id externum    - année d'édition : 2020
//         // - nombre de pages : 300              - code ISBN : 9790412882714
//         // - auteur : Hugues Cartier (id `2`)   - genre : science-fiction (id `6`)
        $books = $bookRepository->findAll();
        $authors = $authorRepository->findAll();
        $genres = $genreRepository->findAll();
        $newBook = new Book();
        $newBook->setTitle('Totum autem id externum');
        $newBook->setYearEdition('2020');
        $newBook->setNumberPages('300');
        $newBook->setCodeIsbn('9790412882714');
        $newBook->setAuthor($authors[1]);
        $newBook->addGenre($genres[5]);
        $entityManager->persist($newBook);
        $entityManager->flush();
        dump($newBook);

//         // Requête de mise à jour d'un livre
//         //- modifier le livre dont l'id est `2` - titre : Aperiendum est igitur - genre : roman d'aventure (id `5`)
        $bookId2 = $bookRepository->find(2);
        $bookId2->setTitle('Aperiendum est igitur');
        $bookId2->addGenre($genres[4]);
        $entityManager->flush();
        dump($bookId2);

        // // Supprimer le livre dont l'id est `123`.
        // $books = $bookRepository->find(123);
        // $book123 = $books[122];

        // // Suppression d'un book.
        // $entityManager->remove($book123);
        // $entityManager->flush();
        // dump($book123);
        

        // Les Emprunteurs
        // Liste complète des emprunteurs
        $borrowers = $borrowerRepository->findAll();
        dump($borrowers);

        // les données de l'emprunteur dont l'id est '3'
        $borrowers = $borrowerRepository->findOneById(3);
        dump($borrowers);

        // les données de l'emprunteur qui est relié à l'user dont l'id est '3'
        $borrowerRepository = $userRepository->findOneById(3);
        dump($borrowers);
        
        // la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`
        $name = 'foo';
        $borrowers = $borrowerRepository->findByFirstnameOrLastname($name);
        dump($borrowers);

        // la liste des emprunteurs dont le téléphone contient le mot clé `1234`
        $phone = '1234';
        $borrowers = $borrowerRepository->findByNumber($phone);
        dump($borrowers);

        // la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit)
        // $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d','2021-03-01'));
        $date = '2021-03-01';
        $borrowers = $borrowerRepository->findOneByCreationDate($date);
        dump($borrowers);

        // la liste des emprunteurs inactifs (c-à-d dont l'attribut `actif` est égal à `false`)
        $borrowerInactif = $borrowerRepository->findByActif(false);
        dump($borrowerInactif);


        // Les emprunts
        // la liste des 10 derniers emprunts au niveau chronologique
        $lastTen = 10;
        $borrowings = $borrowingRepository->findByLastTen($lastTen);
        dump($borrowings);

        // la liste des emprunts de l'emprunteur dont l'id est `2`
        $borrowings = $borrowingRepository->findByBorrowerId(2);
        dump($borrowings);

        // la liste des emprunts du livre dont l'id est `3`
        $borrowings = $borrowingRepository->findByBookId(3);
        dump($borrowings);

        // la liste des emprunts qui ont été retournés avant le 01/01/2021

        $return_date = '2021-01-01';
        $borrowings = $borrowingRepository->findOneByReturnDate($return_date);
        dump($borrowings);

        // // la liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle)
        $return_date = NULL;
        $borrowings = $borrowingRepository->findByReturnDate($return_date);
        dump($borrowings);

        // les données de l'emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés (c-à-d dont la date de retour est nulle)
        $borrowingIdAndReturn = $borrowingRepository->findByIdAndReturn(3);
        dump($borrowingIdAndReturn);
        dump($borrowings);


        // Requête de création
        // - ajouter un nouvel emprunt          - date d'emprunt : 01/12/2020 à 16h00       - date de retour : aucune date      
        // - emprunteur : foo foo (id `1`)      - livre : Lorem ipsum dolor sit amet (id `1`)
        $borrowing = new Borrowing();
        $borrowing->setBorrowingDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
        $borrowing->setBorrower($borrowers[0]);
        $borrowing->setBook($books[0]);
        $entityManager->persist($borrowing);
        $entityManager->flush();

        // Requêtes de mise à jour : - modifier l'emprunt dont l'id est `3` - date de retour : 01/05/2020 à 10h00
        
        $borrowing = $borrowingRepository->findOneById(3);
        $borrowing->setReturnDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));
        dump($borrowing);
        $entityManager->flush();

        // Requêtes de suppression : - supprimer l'emprunt dont l'id est `42`
        $borrowing = $borrowingRepository->findOneById(42);
        $entityManager->remove($borrowing);
        $entityManager->flush();

        exit();
    }
}
