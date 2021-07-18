<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Borrowing;
use App\Entity\Genre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('year_edition')
            ->add('number_pages')
            ->add('code_isbn')         
            // Déclaration d'un champ EntityType
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function(Author $author) {
                    return "{$author->getFirstname()} {$author->getLastname()}";
                },
                // Nécessaire du côté inverse sinon la relation n'est pas enregitrée après mise à jour.
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.firstname', 'ASC')
                        ->orderBy('s.lastname', 'ASC')
                    ;
                },
                'expanded' => true,
            ])

            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => function(Genre $genre) {
                    return "{$genre->getName()} {$genre->getDescription()}";
                },
                // Nécessaire du côté inverse sinon la relation n'est pas enregitrée après mise à jour.
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC')
                        ->orderBy('s.description', 'ASC')
                    ;
                },
                // Many to Many
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('borrowing', EntityType::class, [
                'class' => Borrowing::class,
                'choice_label' => function(Borrowing $borrowing) {
                    return "{$borrowing->getBorrowingDate()} {$borrowing->getReturnDate()}";
                },
                // Nécessaire du côté inverse sinon la relation n'est pas enregitrée après mise à jour.
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.borrowingDate', 'ASC')
                        ->orderBy('s.returnDate', 'ASC')
                    ;
                },
                // One to Many
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
