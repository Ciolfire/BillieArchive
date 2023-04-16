<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\CharacterSkills;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterSkillsType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('academics', HiddenType::class, ['empty_data' => 0])
      ->add('computer', HiddenType::class, ['empty_data' => 0])
      ->add('crafts', HiddenType::class, ['empty_data' => 0])
      ->add('investigation', HiddenType::class, ['empty_data' => 0])
      ->add('medicine', HiddenType::class, ['empty_data' => 0])
      ->add('occult', HiddenType::class, ['empty_data' => 0])
      ->add('politics', HiddenType::class, ['empty_data' => 0])
      ->add('science', HiddenType::class, ['empty_data' => 0])
      ->add('athletics', HiddenType::class, ['empty_data' => 0])
      ->add('brawl', HiddenType::class, ['empty_data' => 0])
      ->add('drive', HiddenType::class, ['empty_data' => 0])
      ->add('firearms', HiddenType::class, ['empty_data' => 0])
      ->add('larceny', HiddenType::class, ['empty_data' => 0])
      ->add('stealth', HiddenType::class, ['empty_data' => 0])
      ->add('survival', HiddenType::class, ['empty_data' => 0])
      ->add('weaponry', HiddenType::class, ['empty_data' => 0])
      ->add('animalKen', HiddenType::class, ['empty_data' => 0])
      ->add('empathy', HiddenType::class, ['empty_data' => 0])
      ->add('expression', HiddenType::class, ['empty_data' => 0])
      ->add('intimidation', HiddenType::class, ['empty_data' => 0])
      ->add('persuasion', HiddenType::class, ['empty_data' => 0])
      ->add('socialize', HiddenType::class, ['empty_data' => 0])
      ->add('streetwise', HiddenType::class, ['empty_data' => 0])
      ->add('subterfuge', HiddenType::class, ['empty_data' => 0]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterSkills::class,
    ]);
  }
}
