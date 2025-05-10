<?php

namespace App\Form;

use App\Entity\Arcanum;
use App\Entity\Legacy;
use App\Entity\MageOrder;
use App\Entity\Path;
use App\Form\Type\RichTextEditorType;
use App\Form\Type\SourceableType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class LegacyType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Legacy */
    $legacy = $options['data'];

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('quote', null, ['label' => "quote", 'translation_domain' => "app"])
      ->add('source', SourceableType::class, [
        'data_class' => Path::class,
        'label' => 'source.label',
        'translation_domain' => "book",
      ])
      ->add('emblem', DropzoneType::class, [
        'label' => 'emblem',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
      ->add('short', null, ['label' => "short", 'translation_domain' => "app"])
      ->add('description', RichTextEditorType::class, [
        'empty_data' => '',
        'data' => $legacy->getDescription(), 
        'label' => "description",
        ])
      ->add('nickname', null, ['label' => "nickname", 'translation_domain' => "app"])
      ->add('path', EntityType::class, [
        'label' => "label.single",
        'translation_domain' => 'path',
        'class' => Path::class,
        'choice_label' => 'name',
        'required' => false,
      ])
      ->add('parentOrder', EntityType::class, [
        'label' => "order.label.single",
        'translation_domain' => 'organization',
        'class' => MageOrder::class,
        'choice_label' => 'name',
        'required' => false,
      ])
      ->add('arcanum', EntityType::class, [
        'label' => "label.single",
        'translation_domain' => 'arcanum',
        'class' => Arcanum::class,
        'choice_label' => 'name',
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('a')->orderBy('a.name', 'ASC');
        },
      ])
      ->add('attainments', CollectionType::class, [
        'label' => "legacy.attainment.label.multi",
        'entry_type' => AttainmentType::class,
        // 'enty_option' => [
        //   'label' => false,
        // ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Legacy::class,
      'translation_domain' => 'app',
    ]);
  }
}
