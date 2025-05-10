<?php

namespace App\Form\Vampire;

use App\Entity\Clan;
use App\Entity\GhoulFamily;
use App\Form\Type\SourceableForm;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Form\Type\RichTextEditorForm;


class GhoulFamilyType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var GhoulFamily */
    $family = $options['data'];
    $translator = $this->translator;

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('source', SourceableForm::class, [
        'data_class' => GhoulFamily::class,
        'label' => 'source.label',
      ])
      ->add('quote', null, ['label' => "quote", 'translation_domain' => "app"])
      ->add('emblem', FileType::class, [
        'label' => 'emblem',
        'mapped' => false,
        'required' => false,
        'translation_domain' => 'app',
        'constraints' => [
          new File([
            'mimeTypes' => [
              'image/*',
            ],
            'mimeTypesMessage' => 'image.invalid',
          ])
        ],
      ])
      ->add('short', null, ['label' => "family.short"])
      ->add('clan', null, [
        'label' => 'family.clan.label',
        'choice_filter' => function (?Clan $clan) {
          return $clan ? !$clan->isBloodline() : false;
        }
      ])
      ->add('nickname', null, ['label' => "nickname", 'translation_domain' => "app"])
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $family->getDescription(),
        'label' => "description",
        'translation_domain' => 'app',
      ])
      ->add('strength', RichTextEditorForm::class, [
        'label' => 'family.strength',
        'empty_data' => '',
        'data' => $family->getWeakness()
      ])
      ->add('weakness', RichTextEditorForm::class, [
        'label' => 'family.weakness',
        'empty_data' => '',
        'data' => $family->getWeakness()
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => GhoulFamily::class,
      "translation_domain" => 'ghoul',
      "allow_extra_fields" => true,
      "is_edit" => false,
    ]);
  }
}
