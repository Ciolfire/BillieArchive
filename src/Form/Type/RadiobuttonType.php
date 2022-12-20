<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RadiobuttonType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
  }

  public function getParent(): ?string
  {
    
    return ChoiceType::class;
  }
}