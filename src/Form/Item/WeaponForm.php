<?php

namespace App\Form\Item;

use App\Entity\Item\Weapon;
use App\Entity\Types\DamageType;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WeaponForm extends ItemForm
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);

    /** @var Weapon $item */
    $item = $options['data'];

    $builder
      ->add('damage', null, [
        'label' => 'damage.label',
      ])
      ->add('damageType', EnumType::class, [
        'class' => DamageType::class,
        'label' => 'damage.type.label',
        'choice_label' => function ($choice) {
          return "damage.{$choice->name}";
        },
        'choice_translation_domain' => 'app',
      ])
      ->add('special', RichTextEditorForm::class, [
        'label' => 'special.label',
        'empty_data' => '',
        'data' => $item->getSpecial(),
        ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Weapon::class,
      'translation_domain' => 'item',
    ]);
  }
}
