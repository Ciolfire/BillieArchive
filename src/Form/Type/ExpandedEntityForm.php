<?php declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpandedEntityForm extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver): void
  {
  }

  public function getParent(): string
  {
    return EntityType::class;
  }
}
