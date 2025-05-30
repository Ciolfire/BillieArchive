<?php

namespace App\Form\Vampire;

use App\Entity\Clan;
use App\Entity\Ghoul;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GhoulForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('regent', null, [
        'label' => 'regent.label',
        'empty_data' => '',
        'required' => false,
      ])
      ->add('clan', null, [
        'label' => 'family.clan.label',
        'choice_filter' => function (?Clan $clan) {
          return $clan ? !$clan->isBloodline() : false;
        }
      ])
      ->add('covenant', null, [
        'label' => 'covenant.label.single',
        'translation_domain' => 'organization',
      ])
      ->add('family', null, [
        'label' => "family.label.single",
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Ghoul::class,
      "translation_domain" => 'ghoul',
    ]);
  }
}
