<?php

namespace App\Form;

use App\Entity\StatusEffect;
use App\Entity\Types\ChoicesStatus;
use App\Entity\Types\VampireChoicesStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class StatusEffectForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    switch ($options['type']) {
      // vampire
      case 'vampire':
        $types = new \ReflectionClass(VampireChoicesStatus::class);
        break;

      default:
        $types = new \ReflectionClass(ChoicesStatus::class);
        break;
    }
    $types = $types->getConstants();
    asort($types);

    $builder
    ->add('type', ChoiceType::class, [
      'label' => false,
      'choices' => $types,
      'choice_translation_domain' => null,
      'choice_label' => function ($choice, string $key): TranslatableMessage|string {
        return new TranslatableMessage("status.type.{$key}", [], 'app');
      },
      'attr' => [
        'data-character--status-target' => 'type',
        'data-action' => 'change->character--status#load',
      ],
      ])
      ->add('choice', ChoiceType::class, [
        'label' => false,
        'attr' => [
          'data-character--status-target' => 'elements',
        ],
        'required' => false,
      ])
      ->add('value', null, [
        'label' => "status.effect.value.label",
        'translation_domain' => 'character',
      ])
      ->add('isLevelDependant', null, [
        'label' => 'status.effect.level.label',
        'help' => 'status.effect.level.help',
        'translation_domain' => 'character',
      ])
      ->add('description', null, [
        'label' => 'status.effect.description.label',
        'translation_domain' => 'character',
      ])
      ->add('locale', HiddenType::class, [
        'label' => "value",
        'mapped' => false,
        'data' => $options['locale'],
        'attr' => [
          'data-character--status-target' => 'locale',
        ]
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
    $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
      if (isset($event->getData()['choice'])) {
        $form = $event->getForm();
        $data = $event->getData()['choice'];
        if ($data) {
          $form->add('choice', ChoiceType::class, ['choices' => [$data => $data]]);
        }
      }
    });
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => StatusEffect::class,
      'translation_domain' => 'app',
      'attr' => [
        'data-controller' => 'character--status',
        'data-form-collection-target' => 'block',
        'class' => "bdr p-2 rounded",
      ],
      'type' => 'null',
      'locale' => 'en',
    ]);
  }
}
