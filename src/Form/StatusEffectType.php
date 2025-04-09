<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Prerequisite;
use App\Entity\StatusEffect;
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

class StatusEffectType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    switch ($options['type']) {
      // vampire
      case 'vampire':
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
      ->add('value', null, ['label' => "value"])
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
      'data_class' => StatusEffect::class,
      'translation_domain' => 'app',
      'attr' => [
        'data-controller' => 'prerequisite',
        'data-form-collection-target' => 'block',
        'class' => "bdr p-2 rounded",
      ],
      'type' => null,
      'homebrew' => null,
    ]);
  }
}
