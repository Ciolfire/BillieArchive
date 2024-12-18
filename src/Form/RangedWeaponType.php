<?php

namespace App\Form;

use App\Entity\Items\RangedWeapon;
use App\Form\Type\RichTextEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class RangedWeaponType extends WeaponType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);

    /** @var RangedWeapon $item */
    $item = $options['data'];

    $builder
      ->add('ranges', null, [
        'label' => 'ranges.label',
        ])
      ->add('clip', null, [
        'label' => 'clip.label',
        ])
      ->add('strength', null, [
        'label' => 'strength.label',
        ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => RangedWeapon::class,
      'translation_domain' => 'item',
    ]);
  }
}
