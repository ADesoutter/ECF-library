<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_index", methods={"GET","POST"})
     */
    public function index(BookRepository $bookRepository,AuthorRepository $authorRepository,Request $request): Response
    {   
        // dump($request->request->all());
        // exit();
        $books= $bookRepository->findAll();
        // dump($books);
        // exit();
        

        
        if ($request->request->all()) {
            $search = $request->request->get('search');
            // dump($search);
            // exit();
            $books = $bookRepository->findByTitleOrAuthor($search);
        }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            
        ]);
    }

}
