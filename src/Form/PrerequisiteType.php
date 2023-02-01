<?php

namespace App\Form;

use App\Entity\Prerequisite;
use App\Entity\Types\ChoicesPrerequisite;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrerequisiteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $types = new \ReflectionClass(ChoicesPrerequisite::class);
    $builder
      ->add('type', ChoiceType::class, [
        'choices' => $types->getConstants(),
        'choice_label' => function ($choice, $key, $value) {
          return $key;
        },
        'attr' => [
          'data-prerequisite-target' => 'type',
          'data-action' => 'change->prerequisite#load',
        ],
      ])
      ->add('entityId', null, [
        'attr' => [
          'data-prerequisite-target' => 'id',
        ],
      ])
      ->add('choice', ChoiceType::class, [
        'attr' => [
          'data-prerequisite-target' => 'list',
          'data-action' => 'change->prerequisite#select',
        ],
        'mapped' => false,
        'required' => false,
      ])
      ->add('value')
      ->add('choiceGroup');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Prerequisite::class,
      'attr' => [
        'data-controller' => 'prerequisite',
      ]
    ]);
  }
}
