<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Borrowing;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('borrowing_date')
            ->add('return_date')
            ->add('book', EntityType::class, [
                // On précise que ce champ permet de gérer la relation avec une entité Book
                'class' => Book::class,
                'choice_label' => function(Book $book) {
                    return "{$book->getName()}";
                },
                // Les books sont triés par ordre croissant (c-à-d alphabétique) du champ titre
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC')
                    ;
                },
            ])
            ->add('borrower', EntityType::class, [
                // On précise que ce champ permet de gérer la relation avec une entité Borrower
                'class' => Borrower::class,
                'choice_label' => function(Borrower $borrower) {
                    return "{$borrower->getLastname()} {$borrower->getFirstname()}";
                },
                // Les emprunteurs sont triés par ordre croissant (c-à-d alphabétique) du champ titre
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.lastname', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Borrowing::class,
        ]);
    }
}
