<?php

namespace App\Form;

use App\Entity\Specialty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialtyType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', null, [
        'attr' => [
          'data-character--creation-target' => 'specialties',
          'data-action' => 'character--creation#specialtyUpdate'
        ]
      ])
      ->add('skill', null, ["label" => 'skill.label']);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Specialty::class,
    ]);
  }
}
