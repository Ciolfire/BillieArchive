<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\CharacterSkills;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Specialty;

use App\Form\SpecialtyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var User */
    $player = $options['data']->getPlayer();
    $builder
      ->add('player')
      ->add('name')
      ->add('age', IntegerType::class, ['attr' => ['min' => 0, 'step' => 1]])
      ->add('virtue')
      ->add('vice')
      ->add('concept')
      ->add('chronicle', EntityType::class, [
        'class' => Chronicle::class,
        'choices' => array_merge($player->getChronicles()->toArray(), $player->getStories()->toArray()),
      ])
      ->add('faction')
      ->add('groupName')
      ->add('race', HiddenType::class, ['mapped' => false, 'data' => 'mortal'])
      ->add('attributes', CharacterAttributesType::class)
      ->add('skills', CharacterSkillsType::class);
      if (!$options['is_edit']) {
        $builder->add('specialty1', SpecialtyType::class, ['mapped' => false, 'label' => false])
        ->add('specialty2', SpecialtyType::class, ['mapped' => false, 'label' => false])
        ->add('specialty3', SpecialtyType::class, ['mapped' => false, 'label' => false]);
      }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Character::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "is_edit" => false,
    ]);
  }
}
