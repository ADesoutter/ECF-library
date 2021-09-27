<?php

namespace App\Controller;

use App\Entity\Borrowing;
use App\Form\BorrowingType;
use App\Repository\BorrowingRepository;
use App\Repository\BookRepository;
use App\Repository\BorrowerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/borrowing")
 */
class BorrowingController extends AbstractController
{
    /**
     * @Route("/", name="borrowing_index", methods={"GET"})
     */
    public function index(BorrowingRepository $borrowingRepository, BorrowerRepository $borrowerRepository ): Response
    {
        // Lister les emprunts
        // Quel est la nature de l'utilisateur
        $user = $this->getUser();

                // On vérifie si l'utilisateur est un student
        // Note : on peut aussi utiliser $this->isGranted('ROLE_STUDENT') au
        // lieu de in_array('ROLE_STUDENT', $user->getRoles()).
        if (in_array('ROLE_BORROWER', $user->getRoles())) {
            // L'utilisateur est un student

            // On récupère le profil student lié au compte utilisateur
            $borrower = $borrowerRepository->findOneByUser($user);

            // On récupère la school year de l'utilisater 
            $borrowings = $borrower->getBorrowings();
            // On créé un tableau avec la school year de l'utilisateur.
            // On est obligé de créer un tableau dans la variable $schoolYears
            // car le template s'attend à ce qu'il puisse boucler sur la
            // variable school_years.
            $borrowing = [$borrowing];
        }


        return $this->render('borrowing/index.html.twig', [
            'borrowings' => $borrowingRepository->findAll(),
        ]);
        
    }


    // Créer un nouvel emprunt
    /**
     * @Route("/new", name="borrowing_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($borrowing);
            $entityManager->flush();

            return $this->redirectToRoute('borrowing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('borrowing/new.html.twig', [
            'borrowing' => $borrowing,
            'form' => $form->createView(),
        ]);
    }

    // Afficher les détails d'un emprunt
    /**
     * @Route("/{id}", name="borrowing_show", methods={"GET"})
     */
    public function show(Borrowing $borrowing, BorrowerRepository $borrowerRepository, BookRepository $bookRepository): Response
    {
        if($this->isGranted('ROLE_BORROWER')) {
            $user = $this->getUser();
            $borrower = $borrowerRepository->findOneByUser($user);
            if (!$borrower->getBorrowings()->contains($borrowing)){
                throw new NotFoundHttpException();
            }
        }
        return $this->render('borrowing/show.html.twig', [
            'emprunt' => $emprunt,
            'livre' => $emprunt->getBook()
        ]);
    }

    // Modifier un borrowing
    /**
     * @Route("/{id}/edit", name="admin_borrowing_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('borrowing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('borrowing/edit.html.twig', [
            'borrowing' => $borrowing,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un emprunt
    /**
     * @Route("/{id}", name="admin_borrowing_delete", methods={"POST"})
     */
    public function delete(Request $request, Borrowing $borrowing): Response
    {
        if ($this->isCsrfTokenValid('delete'.$borrowing->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($borrowing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('borrowing_index', [], Response::HTTP_SEE_OTHER);
    }
}
