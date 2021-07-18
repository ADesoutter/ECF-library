<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('book')
            // Déclaration d'un champ EntityType
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
                // Le champ est à choix multiple
                // One To Many
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
