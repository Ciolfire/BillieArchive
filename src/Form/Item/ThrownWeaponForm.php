<?php

namespace App\Form\Item;

use App\Entity\Item\ThrownWeapon;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class ThrownWeaponForm extends WeaponForm
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);

    /** @var ThrownWeapon $item */
    $item = $options['data'];

    $builder
      ->add('isAerodynamic', null, [
        'label' => 'aerodynamic',
        ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ThrownWeapon::class,
      'translation_domain' => 'item',
    ]);
  }
}
