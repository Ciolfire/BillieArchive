<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('age', IntegerType::class, ['attr' => ['min' => 0, 'step' => 1]])
      ->add('player')
      ->add('virtue')
      ->add('vice')
      ->add('concept')
      ->add('chronicle')
      ->add('faction')
      ->add('groupName')
      ->add('intelligence', HiddenType::class)
      ->add('wits', HiddenType::class)
      ->add('resolve', HiddenType::class)
      ->add('strength', HiddenType::class)
      ->add('dexterity', HiddenType::class)
      ->add('stamina', HiddenType::class)
      ->add('presence', HiddenType::class)
      ->add('manipulation', HiddenType::class)
      ->add('composure', HiddenType::class)
      ->add('merits');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Character::class,
      'translation_domain' => 'character',
    ]);
  }
}
