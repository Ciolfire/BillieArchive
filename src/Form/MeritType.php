<?php

namespace App\Form;

use App\Entity\Merit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeritType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('type')
            ->add('category')
            ->add('isFighting')
            ->add('isExpanded')
            ->add('min')
            ->add('max')
            ->add('isUnique')
            ->add('isCreationOnly')
            ->add('prerequisites')
            ->add('effect')
            ->add('book')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Merit::class,
        ]);
    }
}
