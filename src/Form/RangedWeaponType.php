<?php

namespace App\Form;

use App\Entity\Items\RangedWeapon;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extra\Markdown\LeagueMarkdown;

class RangedWeaponType extends WeaponType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    parent::buildForm($builder, $options);

    $item = $options['data'];

    $builder
      ->add('ranges', null, [
        'label' => 'ranges.label',
        ])
      ->add('clip', null, [
        'label' => 'clip.label',
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
