<?php

namespace App\Form;

use App\Entity\Prerequisite;
use App\Entity\Types\ChoicesPrerequisite;
use App\Entity\Types\ChoicesMeritPrerequisite;
use App\Entity\Types\ChoicesDevotionPrerequisite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class PrerequisiteForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    switch ($options['type']) {
      // WoD
      case 'merit':
        $types = new \ReflectionClass(ChoicesMeritPrerequisite::class);
        break;
      // vampire
      case 'devotion':
        $types = new \ReflectionClass(ChoicesDevotionPrerequisite::class);
        break;

      default:
        $types = new \ReflectionClass(ChoicesPrerequisite::class);
        break;
    }
    $builder
    ->add('type', ChoiceType::class, [
      'label' => 'type',
      'choices' => $types->getConstants(),
      'choice_translation_domain' => 'prerequisite',
      'choice_label' => function ($choice, string $key): TranslatableMessage|string {
        return new TranslatableMessage($key, [], 'prerequisite');
      },
      'attr' => [
        'data-prerequisite-target' => 'type',
        'data-action' => 'change->prerequisite#load',
        'data-prerequisite-type-param' => $options['type'],
        'data-prerequisite-homebrew-param' => $options['homebrew'],
      ],
      ])
      ->add('choice', ChoiceType::class, [
        'label' => 'prerequisite.choice',
        'attr' => [
          'data-prerequisite-target' => 'list',
          'data-action' => 'change->prerequisite#select',
        ],
        'mapped' => false,
        'required' => false,
      ])
      ->add('special', TextType::class, [
        'translation_domain' => 'prerequisite',
        'label' => "special",
        'required' => false,
        'row_attr' => [
          'data-prerequisite-target' => 'special',
          'class' => 'd-none',
        ],
      ])
      ->add('value', null, ['label' => "value"])
      ->add('entityId', HiddenType::class, [
        'attr' => [
          'data-prerequisite-target' => 'id',
        ],
        'empty_data' => 0,
      ])
      ->add('choiceGroup', null, [
        'label' => 'choice.label',
        'help' => "help.choice",
      ])
      ->add('remove', ButtonType::class, [
        'attr' => [
          'data-action' => 'form-collection#removeCollectionElement',
          'class' => 'btn-warning w-25',
        ],
        'row_attr' => [
          'class' => 'text-center',
        ],
        'label' => 'action.remove',
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Prerequisite::class,
      'translation_domain' => 'app',
      'type' => null,
      'homebrew' => null,
      'attr' => [
        'data-controller' => 'prerequisite',
        'data-form-collection-target' => 'block',
        'class' => "bdr p-2 rounded",
      ],
    ]);
  }
}
