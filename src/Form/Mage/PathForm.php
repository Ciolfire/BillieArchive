<?php

namespace App\Form\Mage;

use App\Entity\Path;
use App\Form\Type\SourceableForm;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

class PathForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Path */
    $path = $options['data'];
    
    $translator = $this->translator;

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('title', null, ['label' => "title", 'translation_domain' => "app"])
      ->add('source', SourceableForm::class, [
        'data_class' => Path::class,
        'label' => 'source.label',
        'translation_domain' => "book",
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
      ->add('symbol', DropzoneType::class, [
        'label' => 'symbol.label',
        'mapped' => false,
        'required' => false,
        'translation_domain' => 'mage',
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
      ->add('rune', DropzoneType::class, [
        'label' => 'rune.label',
        'translation_domain' => 'mage',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
      ->add('short', null, ['label' => "short", 'translation_domain' => "app"])
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $path->getDescription(), 
        'label' => "description",
        'translation_domain' => 'app',
        ])
      ->add('nimbus', null, ['label' => "nimbus"])
      ->add('attribute', null, [
          'expanded' => true,
          'translation_domain' => "attribute",
          'label' => 'label.multi',
          'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('a')->where("a.type = 'resistance'")->orderBy('a.name', 'ASC');
          },
        ])
      ->add('rulingArcana', null, [
        'label' => 'ruling',
        "translation_domain" => 'arcanum',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('inferiorArcanum', null, [
        'label' => 'inferior',
        "translation_domain" => 'arcanum',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Path::class,
      "translation_domain" => 'path',
      // "allow_extra_fields" => true,
      // "is_edit" => false,
    ]);
  }
}
