<?php

namespace App\Form;

use App\Entity\Items\Vehicle;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class VehicleType extends ItemType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);
    
    $item = $options['data'];

    $builder
      ->add('handling', null, [
        'label' => 'handling.label',
      ])
      ->add('acceleration', null, [
        'label' => 'acceleration.label',
        ])
      ->add('safeSpeed', null, [
        'label' => 'safeSpeed.label',
        ])
      ->add('maxSpeed', null, [
        'label' => 'maxSpeed.label',
        ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Vehicle::class,
      'translation_domain' => 'item',
    ]);
  }
}
