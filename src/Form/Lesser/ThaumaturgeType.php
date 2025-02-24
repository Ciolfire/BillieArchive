<?php

namespace App\Form\Lesser;

use App\Entity\Thaumaturge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThaumaturgeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('tradition', null, [
        'label' => 'tradition.label.single',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Thaumaturge::class,
      "translation_domain" => 'thaumaturge',
    ]);
  }
}
