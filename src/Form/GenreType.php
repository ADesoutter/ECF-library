<?php

namespace App\Form;

use App\Entity\Genre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
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
                // Many To Many
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
