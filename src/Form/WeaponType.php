<?php

namespace App\Form;

use App\Entity\Items\Weapon;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extra\Markdown\LeagueMarkdown;

class WeaponType extends ItemType
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
      ->add('damage', null, [
        'label' => 'damage.label',
      ])
      ->add('strength', null, [
        'label' => 'strength.label',
        ])
      ->add('special', CKEditorType::class, [
        'label' => 'special.label',
        'empty_data' => '',
        'data' => $converter->convert($item->getDescription()),
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
