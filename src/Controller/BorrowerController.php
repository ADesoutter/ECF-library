<?php

namespace App\Controller;

use App\Entity\Borrower;
use App\Form\BorrowerType;
use App\Repository\BorrowerRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/borrower")
 */
class BorrowerController extends AbstractController
{
    /**
     * @Route("/", name="borrower_index", methods={"GET"})
     */
    public function index(BorrowerRepository $borrowerRepository): Response
    {
        return $this->render('borrower/index.html.twig', [
            'borrowers' => $borrowerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="borrower_show", methods={"GET"})
     */
    public function show(Borrower $borrower): Response
    {
        return $this->render('borrower/show.html.twig', [
            'borrower' => $borrower,
        ]);
    }

    private function redirectUser(string $route, Borrower $borrower, BorrowerRepository $borrowerRepository)
    {
        $user = $this->getUser();


        if (in_array('ROLE_BORROWER', $user->getRoles())) {
            $userBorrower = $borrowerRepository->findOneByUser($user);


            if ($borrower->getId() != $userBorrower->getId()) {
                return $this->redirectToRoute($route, [
                    'id' => $userBorrower->getId(),
                ]);
            }
        }

        return null;
    }
}