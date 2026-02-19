<?php

namespace App\Form\Creation;

use App\Entity\Covenant;
use App\Entity\Vampire;
use App\Form\CharacterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VampireForm extends CharacterForm
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    parent::configureOptions($resolver);

    // $resolver->setDefaults([
    //   "data_class" => Character::class,
    //   "translation_domain" => 'character',
    //   "allow_extra_fields" => true,
    //   "name" => "human",
    //   "is_edit" => false,
    //   "user" => null,
    // ]);
  }
}