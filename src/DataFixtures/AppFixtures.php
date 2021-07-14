<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Borrowing;
use App\Entity\Genre;
use App\Entity\User;
use Faker\Factory as FakerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = \Faker\Factory::create('fr_FR');
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        $authorCount = 500;
        $booksCount = 1000;
        $borrowerCount = 100;
        $borrowingsCount = 200;
        $genreCount = 10;

        // Création du tableau Genre
        $listGenre = ['Poésie', 'Nouvelle', 'Roman historique', 'Roman d\'amour', 'Roman d\'aventure', 'Science-fiction', 'Fantaisy', 'Biographie', 'Conte', 'Témoignage', 'Théâtre', 'Essai', 'Journal intime'];

        // Appel des fonctions qui vont créer les objets dans la BDD.
        // La fonction loadAdmins() ne renvoit pas de données mais les autres
        // fontions renvoit des données qui sont nécessaires à d'autres fonctions.
        $this->loadAdmins($manager);
        $borrowers = $this->loadBorrowers($manager, $borrowerCount);
        $authors = $this->loadAuthors($manager, $authorCount);
        $genres = $this->loadGenres($manager, $listGenre);
        $books = $this->loadBooks($manager, $authors, $genres, $booksCount);
        $borrowings = $this->loadBorrowings($manager, $books, $borrowers, $borrowingsCount);   

        // Exécution des requêtes.
        // C-à-d envoi de la requête SQL à la BDD.
        $manager->flush();
    }

    public function loadAdmins(ObjectManager $manager)
    {
        //L' utilisateur avec un role ADMIN
        $user = new User();
        $user->setEmail('admin@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
    }

    public function loadAuthors(ObjectManager $manager, int $count)
    {
        $authors = [];

        // Création de l'auteur inconnu (id:1)
        $author = new Author();
        $author->setLastname('unknown author');
        $author->setFirstname(' ');
        $manager->persist($author);
        $authors[] = $author;

        // Création de l'auteur (id:2)
        $author = new Author();
        $author->setLastname('Cartier');
        $author->setFirstname('Hugues');
        $manager->persist($author);
        $authors[] = $author;

        // Création de l'auteur (id:3)
        $author = new Author();
        $author->setLastname('Lambert');
        $author->setFirstname('Armand');
        $manager->persist($author);
        $authors[] = $author;

        // Création de l'auteur (id:4)
        $author = new Author();
        $author->setLastname('Moitessier');
        $author->setFirstname('Thomas');
        $manager->persist($author);
        $authors[] = $author;

    // Création d'auteurs avec faker et la boucle
        for($i = 3; $i < 500; $i++) {
            $author = new Author();
            $author->setLastname($this->faker->lastname());
            $author->setFirstname($this->faker->firstname());
            $manager->persist($author);
            $authors[] = $author;
        }
        return $authors;
    }


    public function loadBooks(ObjectManager $manager, array $authors, $genres, int $count)
    {

        $books = [];

        $book = new Book();
        $book->setTitle('Lorem ipsum dolor sit amet');
        $book->setYearEdition('2010');
        $book->setNumberPages('100');
        $book->setCodeIsbn('9785786930024');
        $book->setAuthor($authors[0]);
        $book->addGenre($genres[0]);

        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Consectetur adipiscing elit');
        $book->setYearEdition('2011');
        $book->setNumberPages('150');
        $book->setCodeIsbn('9783817260935');
        $book->setAuthor($authors[1]);
        $book->addGenre($genres[1]);

        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Mihi quidem Antiochum');
        $book->setYearEdition('2012');
        $book->setNumberPages('200');
        $book->setCodeIsbn('9782020493727');
        $book->setAuthor($authors[2]);
        $book->addGenre($genres[2]);

        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Quem audis satis belle');
        $book->setYearEdition('2013');
        $book->setNumberPages('250');
        $book->setCodeIsbn('979459561353');
        $book->setAuthor($authors[3]);
        $book->addGenre($genres[3]);

        $manager->persist($book);
        $books[] = $book;

    // Création de livres avec faker et la boucle
        for($i = 3; $i < $count; $i++) {

            $book->setTitle($this->faker->realTextBetween($min = 6, $max = 12));
            $book->setYearEdition($this->faker->numberBetween($min = 2000, $max = 2020));
            $book->setNumberPages($this->faker->numberBetween($min = 100, $max = 300));
            $book->setCodeIsbn($this->faker->isbn13());

            // relations : Many to One avec author : set
            $book->setAuthor($this->faker->randomElement($authors));
            // relations : Many to Many avec genre : add
            $book->addGenre($this->faker->randomElement($genres));

            $manager->persist($book);
            $books[] = $book;
        }

        return $books;
    }

    
    public function loadGenres(ObjectManager $manager, array $listGenre)
    {

        $genres = [];

        $genre = new Genre();
        $genre->setName($listGenre[0]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[1]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[2]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[3]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[4]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[5]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[6]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[7]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[8]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[9]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[10]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[11]);
        $genre->setDescription("NULL");
        $manager->persist($genre);
        $genres[] = $genre;

        $genre = new Genre();
        $genre->setName($listGenre[12]);
        $manager->persist($genre);
        $genres[] = $genre;

        return $genres;
      
    }

    public function loadBorrowings(ObjectManager $manager, array $borrowers, $books, int $count)
    {

        $borrowings = [];

        // 1er Emprunt
        $borrowing = new Borrowing();
        $borrowing->setBorrowingDate (\DateTime::createFromFormat('Y-m-d H:i:s','2020-02-01 10:00:00'));
        $borrowingDate = $borrowing->getBorrowingDate();
        $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s',  $borrowingDate->format('Y-m-d H:i:s'));
        // ajout d'un interval d' 1 mois à la date de début   
        $modificationDate->add(new \DateInterval('P1M'));
        $borrowing->setReturnDate($modificationDate);
        $borrowing->setBorrower($borrowers[0]);
        $borrowing->setBook($books[0]);

        $manager->persist($borrowing);
        $borrowings[] = $borrowing;

        // 2ème emprunt
        $borrowing = new Borrowing();
        $borrowing->setBorrowingDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'));
        $borrowingDate = $borrowing->getBorrowingDate();
        $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s',  $borrowingDate->format('Y-m-d H:i:s'));
        // ajout d'un interval d' 1 mois à la date de début
        $modificationDate->add(new \DateInterval('P1M'));
        $borrowing->setReturnDate($modificationDate);
        $borrowing->setBorrower($borrowers[1]);
        $borrowing->setBook($books[1]);

        $manager->persist($borrower);
        $borrowings[] = $borrowing;

        // 3ème emprunt
        $borrowing = new Borrowing();
        $borrowing->setBorrowingDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'));
        $borrowingDate = $borrowing->getBorrowingDate();
        $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s',  $borrowingDate->format('Y-m-d H:i:s'));
        $borrowing->setReturnDate(NULL);
        $borrowing->setBorrower($borrowers[2]);
        $borrowing->setBook($books[2]);

        $manager->persist($borrower);
        $borrowings[] = $borrowing;


        for($i = 2; $i < $count; $i++) {

            $borrowing = new Borrowing();
            $borrowing->setBorrowingDate($this->faker->dateTime());
            $borrowingDate = $borrowing->getBorrowingDate();
            // création de la date de début
            $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s',  $borrowingDate->format('Y-m-d H:i:s'));

            // Relation Many to One avec Borrower
            $borrowing->setBorrower($this->faker->randomElement($borrowers));
            // Relation Many to One avec Book
            $borrowing->setBook($this->faker->randomElement($books));

            $manager->persist($borrowing);
            $borrowings[] = $borrowing;
        }
        return $borrowings;
    }

    public function loadBorrowers(ObjectManager $manager, int $count)
    {

        $borrowers = [];

        // Création d'un User avec un role emprunteur foo
        $user = new User();
        $user->setEmail('foo.foo@example.com');
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setLastname('foo');
        $borrower->setFirstname('foo');
        $borrower->setPhone('123456789');
        $borrower->setActif(true);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s','2020-01-01 10:00:00'));
        $borrower->setModificationDate(NULL);
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;

        // Création d'un User avec un role emprunteur bar
        $user = new User();
        $user->setEmail('bar.bar@example.com');
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setLastname('bar');
        $borrower->setFirstname('bar');
        $borrower->setPhone('123456789');
        $borrower->setActif(false);
        $borrower->setCreationDate(\DateTime::createFromFormat('2020-02-01 11:00:00'));
        $borrower->setModificationDate(\DateTime::createFromFormat('2020-05-01 12:00:00'));
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;

        // Création d'un User avec un role emprunteur baz
        $user = new User();
        $user->setEmail('baz.baz@example.com');
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setLastname('baz');
        $borrower->setFirstname('baz');
        $borrower->setPhone('123456789');
        $borrower->setActif(true);
        $borrower->setCreationDate(\DateTime::createFromFormat('2020-03-01 12:00:00'));
        $borrower->setModificationDate(NULL);
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;

 
        // Création d'une boucle pour générer 100 utilisateurs au total
        for($i = 3; $i < $count; $i++) {
        $user = new User();
        $user->setEmail($this->faker->email());
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $manager->persist($user);
        

        // Création d'un emprunteur avec des données aléatoires
        // qui reste dans la boucle afin que chaque emprunteur soit lié à un compte utilisateur
        $borrower = new Borrower();
        $borrower->setLastname($this->faker->lastname());
        $borrower->setFirstname($this->faker->firstname());
        $borrower->setPhone($this->faker->phoneNumber());
        $borrower->setActif($this->faker->boolean);
        // utilisation de DateTimeThisYear
        $borrower->setCreationDate($this->faker->dateTimeThisYear());
        $creationDate = \DateTime::createFromFormat('Y-m-d H:i:s');
        $borrower->setModificationDate($this->faker->dateTimeThisYear());
        $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s');
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;
        }
        
        return $borrowers;
    }
    
}
