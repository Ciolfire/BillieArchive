<?php

namespace App\Form\Lesser;

use App\Entity\BodyThief;
use App\Entity\Types\BodyThiefTalent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BodyThiefType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('talent', EnumType::class, [
        'class' => BodyThiefTalent::class,
        'required' => false,
        'label' => 'talent.label',
        'help' => 'talent.help',
        'choice_label' => function ($choice) {
          return "talent.{$choice->name}";
        }
      ])
      ->add('society', null, [
        'label' => 'society.label.single',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => BodyThief::class,
      "translation_domain" => 'body-thief',
    ]);
  }
}
