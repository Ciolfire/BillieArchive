<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Prerequisite;
use App\Entity\Types\ChoicesPrerequisite;
use App\Entity\Types\ChoicesMeritPrerequisite;
use App\Entity\Types\ChoicesDevotionPrerequisite;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class PrerequisiteType extends AbstractType
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
      'label' => 'type.label',
      'choices' => $types->getConstants(),
      'choice_label' => function ($choice, string $key): TranslatableMessage|string {
        return new TranslatableMessage($key, [], 'prerequisite');
      },
      'attr' => [
        'data-prerequisite-target' => 'type',
        'data-action' => 'change->prerequisite#load',
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
      ->add('value', null, ['label' => "value"])
      ->add('entityId', HiddenType::class, [
        'attr' => [
          'data-prerequisite-target' => 'id',
        ],
      ])
      ->add('choiceGroup', null, [
        'label' => 'prerequisite.group',
        'row_attr' => [
          'class' => 'border-bottom'
        ],
      ])
      ->add('remove', ButtonType::class, [
        'attr' => [
          'data-action' => 'form-collection#removeCollectionElement',
        ]
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Prerequisite::class,
      'translation_domain' => 'app',
      'attr' => [
        'data-controller' => 'prerequisite',
        'data-form-collection-target' => 'block',
      ],
      'type' => null,
    ]);
  }
}
