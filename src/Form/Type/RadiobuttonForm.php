<?php declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RadiobuttonForm extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver) : void
  {
    parent::configureOptions($resolver);
  }

  public function getParent(): ?string
  {
    
    return ChoiceType::class;
  }
}