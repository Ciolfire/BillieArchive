<?php

namespace App\Form\Lesser;

use App\Entity\BloodBathFacet;
use App\Form\Type\RichTextEditorType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BloodFacetForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $facet = $options['attr']['data-bloodbather--bath-param'];

    $builder
      ->add('choice', EntityType::class, [
        'class' => BloodBathFacet::class,
        'query_builder' => function (EntityRepository $er) use ($facet) {
          return $er->queryfindByFacet($facet);
        },
        'required' => false,
        'mapped' => false,
        'placeholder' => "bath.facet.custom",
        'choice_attr' => function ($choice) {
          return [
            'data-title' => $choice->getLabel(),
            'data-description' => $choice->getDescription(),
            'data-modifier' => $choice->getModifier(),
          ];
        },
        'attr' => [
          'data-action' => 'change->bloodbather--bath#load',
          'data-bloodbather--bath-target' => "choice",
        ],
      ])
      ->add('title', null, [
        'attr' => [
          'data-bloodbather--bath-target' => "title",
        ],
        'row_attr' => [
          'class' => "d-none",
        ]
      ])
      ->add('description', RichTextEditorType::class, [
        'attr' => [
          'data-bloodbather--bath-target' => "description",
          'data-rich-preview-style' => 'tab'
        ],
        'label' => false,
        'row_attr' => [
          'class' => "d-none",
        ]
      ])
      ->add('modifier', null, [
        'attr' => [
          'data-bloodbather--bath-target' => "modifier",
        ],
        'row_attr' => [
          'class' => "d-none",
        ]
      ])
      ->add('remove', ButtonType::class, [
        'attr' => [
          'data-action' => 'form-collection#removeCollectionElement',
          'class' => 'btn-warning w-25',
        ],
        'row_attr' => [
          'class' => 'text-center',
        ],
        'label' => 'action.remove',
        'translation_domain' => 'app',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'translation_domain' => 'blood-bather',
      'attr' => [
        'data-controller' => 'bloodbather--bath',
        'data-form-collection-target' => 'block',
        'class' => "bdr p-2 rounded",
        'data-bloodbather--bath-param' => "bath",
      ],
      'label' => false,
    ]);
  }
}
