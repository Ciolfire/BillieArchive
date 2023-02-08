<?php

namespace App\Form;

use App\Entity\Vampire;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VampireType extends CharacterType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Vampire */
    $vampire = $options['data'];
    
    // get the parent form fields
    parent::buildForm($builder, $options);

    $builder
      ->add('deathAge', null, [
        'label' => false,
        'translation_domain' => 'vampire',
        'attr' => [
          'placeholder' => 'embrace.at'
        ],
      ])
      ->add('sire', null, [
        'label' => false,
        'translation_domain' => 'vampire',
        'attr' => [
          'placeholder' => 'embrace.by'
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Vampire::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "is_edit" => false,
      "name" => 'character',
    ]);
  }
}
