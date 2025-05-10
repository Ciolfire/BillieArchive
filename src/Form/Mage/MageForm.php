<?php

namespace App\Form\Mage;

use App\Entity\Mage;
use App\Form\CharacterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MageForm extends CharacterForm
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    // get the parent form fields
    parent::buildForm($builder, $options);

    $builder
      ->add('order', null, [
        'label' => 'order.label.single',
        'translation_domain' => 'organization'
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Mage::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "is_edit" => false,
      "name" => 'character',
      "user" => null,
    ]);
  }
}
