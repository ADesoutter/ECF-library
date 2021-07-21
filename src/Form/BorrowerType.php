<?php

namespace App\Form;

use App\Entity\Borrower;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('user', UserType::class, [
            'label_attr' => [
                'class' => 'd-none',
            ]
        ])
            ->add('lastname')
            ->add('firstname')
            ->add('phone')
            ->add('actif')
            ->add('creation_date')
            ->add('modification_date')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Borrower::class,
        ]);
    }
}
