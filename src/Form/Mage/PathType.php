<?php declare(strict_types=1);

namespace App\Form\Mage;

use App\Entity\Path;
use App\Form\Type\SourceableType;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorType;
use Symfony\UX\Dropzone\Form\DropzoneType;

class PathType extends AbstractType
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
      ->add('source', SourceableType::class, [
        'data_class' => Path::class,
        'label' => 'source.label',
        'translation_domain' => "book",
      ])
      ->add('emblem', DropzoneType::class, [
        'label' => 'emblem',
        'translation_domain' => 'app',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
      ->add('symbol', DropzoneType::class, [
        'label' => 'symbol.label',
        'translation_domain' => 'mage',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
      ->add('rune', DropzoneType::class, [
        'label' => 'rune.label',
        'translation_domain' => 'mage',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
      ->add('short', null, ['label' => "short", 'translation_domain' => "app"])
      ->add('description', RichTextEditorType::class, [
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
