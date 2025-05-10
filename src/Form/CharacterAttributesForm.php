<?php

namespace App\Form;

use App\Entity\CharacterAttributes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterAttributesForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('intelligence', HiddenType::class)
      ->add('wits', HiddenType::class)
      ->add('resolve', HiddenType::class)
      ->add('strength', HiddenType::class)
      ->add('dexterity', HiddenType::class)
      ->add('stamina', HiddenType::class)
      ->add('presence', HiddenType::class)
      ->add('manipulation', HiddenType::class)
      ->add('composure', HiddenType::class);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterAttributes::class,
    ]);
  }
}
