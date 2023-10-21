<?php

namespace App\Form;

use App\Entity\Clan;
use App\Entity\Ghoul;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GhoulType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('regent', null, [
        'label' => 'ghoul.regent.label',
        'empty_data' => '',
      ])
      ->add('clan', null, [
        'label' => 'ghoul.family.clan.label',
        'choice_filter' => function (?Clan $clan) {
          return $clan ? !$clan->isBloodline() : false;
        }
      ])
      // ->add('family')
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Ghoul::class,
      "translation_domain" => 'vampire',
    ]);
  }
}
