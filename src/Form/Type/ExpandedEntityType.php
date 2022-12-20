<?php

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpandedEntityType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver): void
  {
  }

  public function getParent(): string
  {
    return EntityType::class;
  }
}
