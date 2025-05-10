<?php

namespace App\Form;

use App\Entity\Items\Weapon;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class WeaponType extends ItemType
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
