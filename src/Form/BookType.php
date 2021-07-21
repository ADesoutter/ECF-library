<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
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

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('s.firstname', 'ASC')
                        ->orderBy('s.lastname', 'ASC')
                    ;
                },
            ])

            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => function(Genre $genre) {
                    return "{$genre->getName()}";
                },

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('s.name', 'ASC')
                    ;
                },
                // Many to Many
                'multiple' => true,
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
