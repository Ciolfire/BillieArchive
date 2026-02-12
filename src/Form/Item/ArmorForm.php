<?php

namespace App\Form\Item;

use App\Entity\Item\Armor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArmorForm extends ItemForm
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);
    
    $item = $options['data'];

    $builder
      ->add('ratingMelee', null, [
        'label' => 'rating.melee.label',
        'help' => 'help.armor.rating.melee',
      ])
      ->add('ratingRanged', null, [
        'label' => 'rating.ranged.label',
        'help' => 'help.armor.rating.ranged',
      ])
      ->add('isBulletproof', null, [
        'label' => 'bulletproof.label',
        'help' => 'help.armor.bulletproof',
      ])
      ->add('strength', null, [
        'label' => 'strength.label',
        'help' => 'help.armor.strength',
      ])
      ->add('defense', null, [
        'label' => 'defense.label',
        'help' => 'help.armor.defense',
      ])
      ->add('speed', null, [
        'label' => 'speed.label',
        'help' => 'help.armor.speed',
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Armor::class,
      'translation_domain' => 'item',
    ]);
  }
}
