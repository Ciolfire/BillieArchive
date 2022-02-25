<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Specialty;

use App\Form\SpecialtyType;
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
      ->add('academics', HiddenType::class, ['mapped' => false])
      ->add('computer', HiddenType::class, ['mapped' => false])
      ->add('crafts', HiddenType::class, ['mapped' => false])
      ->add('investigation', HiddenType::class, ['mapped' => false])
      ->add('medecine', HiddenType::class, ['mapped' => false])
      ->add('occult', HiddenType::class, ['mapped' => false])
      ->add('politics', HiddenType::class, ['mapped' => false])
      ->add('science', HiddenType::class, ['mapped' => false])
      ->add('athletics', HiddenType::class, ['mapped' => false])
      ->add('brawl', HiddenType::class, ['mapped' => false])
      ->add('drive', HiddenType::class, ['mapped' => false])
      ->add('firearms', HiddenType::class, ['mapped' => false])
      ->add('larceny', HiddenType::class, ['mapped' => false])
      ->add('stealth', HiddenType::class, ['mapped' => false])
      ->add('survival', HiddenType::class, ['mapped' => false])
      ->add('weaponry', HiddenType::class, ['mapped' => false])
      ->add('animal_ken', HiddenType::class, ['mapped' => false])
      ->add('empathy', HiddenType::class, ['mapped' => false])
      ->add('expression', HiddenType::class, ['mapped' => false])
      ->add('intimidation', HiddenType::class, ['mapped' => false])
      ->add('persuasion', HiddenType::class, ['mapped' => false])
      ->add('socialize', HiddenType::class, ['mapped' => false])
      ->add('streetwise', HiddenType::class, ['mapped' => false])
      ->add('subterfuge', HiddenType::class, ['mapped' => false])
      ->add('specialty1', SpecialtyType::class, ['mapped' => false, 'label' => false])
      ->add('specialty2', SpecialtyType::class, ['mapped' => false, 'label' => false])
      ->add('specialty3', SpecialtyType::class, ['mapped' => false, 'label' => false])
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
