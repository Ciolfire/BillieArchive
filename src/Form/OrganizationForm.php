<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Organization;
use App\Form\Type\SourceableForm;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;
use App\Form\Type\RichTextEditorForm;

class OrganizationForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Organization */
    $organization = $options['data'];
    $item = $options['item'];

    if ($item instanceof Book) {
      $organization->setBook($item);
      $builder->add('source', SourceableForm::class, [
        'data_class' => Organization::class,
        'label' => 'source.label',
      ]);
    } else if ($item instanceof Chronicle) {
      $organization->setHomebrewFor($item);
    } else {
      $builder->add('source', SourceableForm::class, [
        'data_class' => Organization::class,
        'label' => 'source.label',
      ]);
    }

    $builder
      ->add('name', null, [
        'label' => 'name',
        'translation_domain' => 'app',
      ])
      ->add('emblem', DropzoneType::class, [
        'label' => false,
        'translation_domain' => 'app',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
      ->add('short', null, [
        'label' => "short",
        'translation_domain' => 'app',
      ])
      ->add('isPrivate', null, [
        'label' => "private",
      ])
      ->add('description', RichTextEditorForm::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'description',
        'empty_data' => '',
        'data' => $organization->getDescription(),
      ])
      ->add('overview', RichTextEditorForm::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'overview',
        'empty_data' => '',
        'data' => $organization->getOverview(),
      ])
      ->add('members', RichTextEditorForm::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'members',
        'empty_data' => '',
        'data' => $organization->getMembers(),
      ])
      ->add('philosophy', RichTextEditorForm::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'philosophy',
        'empty_data' => '',
        'data' => $organization->getPhilosophy(),
      ])
      ->add('observances', RichTextEditorForm::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'observances',
        'empty_data' => '',
        'data' => $organization->getObservances(),
      ])
      ->add('titles', RichTextEditorForm::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'titles',
        'empty_data' => '',
        'data' => $organization->getTitles(),
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Organization::class,
      'translation_domain' => 'organization',
      'item' => null,
    ]);
  }
}
