<?php

namespace App\Form\Vampire;

use App\Entity\Attribute;
use App\Entity\Clan;
use App\Form\Type\SourceableForm;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Form\Type\RichTextEditorForm;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ClanForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Clan */
    $clan = $options['data'];
    
    $translator = $this->translator;

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('quote', null, ['label' => "quote", 'translation_domain' => "app"])
      ->add('source', SourceableForm::class, [
        'data_class' => Clan::class,
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
          new File([
            'mimeTypes' => [
              'image/*',
            ],
            'mimeTypesMessage' => 'image.invalid',
          ])
        ],
      ])
      ->add('symbol', DropzoneType::class, [
        'label' => 'symbol',
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
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $clan->getDescription(), 
        'label' => "description",
        'translation_domain' => 'app',
      ]);
      if ($clan->isBloodline()) {
        $builder->add('parentClan', null, [
            'label' => 'bloodline.parent.label',
            'choice_filter' => function (?Clan $parent) use ($clan) {
              return $parent ? (!$parent->isBloodline() && $parent->isAncient() == $clan->isAncient()) : false;
            }
          ]
        );
      } else {
        $builder->add('attributes', null, [
          'expanded' => true,
          'translation_domain' => "attribute",
          'label' => 'label.multi',
          'group_by' => function($choice) use ($translator) {
            /** @var Attribute $choice */
            return $translator->trans("category.{$choice->getCategory()}", [], 'app');
          },
        ]);
      }
      $builder->add('nickname', null, ['label' => "nickname", 'translation_domain' => "app"])
      ->add('short', null, ['label' => "short", 'translation_domain' => "app",])
      ->add('weakness', RichTextEditorForm::class, [
        'label' => 'weakness',
        'empty_data' => '',
        'data' => $clan->getWeakness()
      ])
      ->add('disciplines', null, [
        'label' => 'label.multi',
        "translation_domain" => 'discipline',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('keywords', null, ['label' => 'keywords'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Clan::class,
      'translation_domain' => "clan",
      'allow_extra_fields' => true,
      'is_edit' => false,
    ]);
  }
}
