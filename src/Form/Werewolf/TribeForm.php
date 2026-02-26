<?php

namespace App\Form\Werewolf;

use App\Entity\GiftList;
use App\Entity\Renown;
use App\Entity\Tribe;
use App\Form\Type\SourceableForm;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Form\Type\RichTextEditorForm;
use Symfony\UX\Dropzone\Form\DropzoneType;

class TribeForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Tribe */
    $tribe = $options['data'];

    $translator = $this->translator;

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('nickname', null, ['label' => "nickname", 'translation_domain' => "app"])
      ->add('quote', null, ['label' => "quote", 'translation_domain' => "app"])
      ->add('source', SourceableForm::class, [
        'data_class' => Tribe::class,
        'label' => 'source.label',
        'translation_domain' => "book"
      ])
      ->add('emblem', DropzoneType::class, [
        'label' => 'emblem',
        'mapped' => false,
        'required' => false,
        'translation_domain' => 'app',
        'attr' => ['placeholder' => 'upload'],
        'constraints' => [
          new File(
            mimeTypes: [
              'image/*',
            ],
            mimeTypesMessage: 'image.invalid',
          )
        ],
      ])
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $tribe->getDescription(),
        'label' => "description",
        'translation_domain' => 'app',
      ])
      ->add('short', null, ['label' => "short", 'translation_domain' => "app",])
      ->add('gifts', EntityType::class, [
        'class' => GiftList::class,
        'label' => 'label.multi',
        'translation_domain' => 'gift',
        'choice_label' => 'name',
        'expanded' => true,
        'multiple' => true,
      ])
      ->add('renown', EntityType::class, [
        'class' => Renown::class,
        'label' => 'label.single',
        'translation_domain' => "renown",
        'choice_label' => 'name',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Tribe::class,
    ]);
  }
}
