<?php

namespace App\Form\Vampire;

use App\Entity\Covenant;
use App\Entity\Vampire;
use App\Form\CharacterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VampireForm extends CharacterForm
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $character = $options['data'];
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
      ])
      ->add('covenant', null, [
        'label' => 'covenant.label.single',
        'translation_domain' => 'organization',
        'choice_filter' => function (?Covenant $covenant) use ($character) {
          return $covenant ? 
          $covenant->getType() == "covenant" && 
          ($covenant->getHomebrewFor() === $character->getChronicle() ||
          $covenant->getHomebrewFor() == null) && 
          $covenant->isAncient() == $character->isAncient() : true;
        },
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Vampire::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "is_edit" => false,
      "name" => 'character',
      "user" => null,
    ]);
  }
}
