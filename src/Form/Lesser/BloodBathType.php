<?php

namespace App\Form\Lesser;

use App\Entity\BloodBath;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BloodBathType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('bath', CollectionType::class, [
        'label' => false,
        'help' =>"bath.help",
        'entry_type' => BloodFacetType::class,
        'entry_options' => [
          'attr' => [
            'data-bloodbather--bath-param' => "bath",
            'data-controller' => 'bloodbather--bath',
            'data-form-collection-target' => 'block',
            'class' => "bdr p-2 rounded",
          ],
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('blood', CollectionType::class, [
        'label' => false,
        'help' => "blood.help",
        'entry_type' => BloodFacetType::class,
        'entry_options' => [
          'attr' => [
            'data-bloodbather--bath-param' => "blood",
            'data-controller' => 'bloodbather--bath',
            'data-form-collection-target' => 'block',
            'class' => "bdr p-2 rounded",
          ],
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('effects', CollectionType::class, [
        'label' => false,
        'help' => "effects.help",
        'entry_type' => BloodFacetType::class,
        'entry_options' => [
          'attr' => [
            'data-bloodbather--bath-param' => "effects",
            'data-controller' => 'bloodbather--bath',
            'data-form-collection-target' => 'block',
            'class' => "bdr p-2 rounded",
          ],
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('frequency', CollectionType::class, [
        'label' => false,
        'help' => "frequency.help",
        'entry_type' => BloodFacetType::class,
        'entry_options' => [
          'attr' => [
            'data-bloodbather--bath-param' => "frequency",
            'data-controller' => 'bloodbather--bath',
            'data-form-collection-target' => 'block',
            'class' => "bdr p-2 rounded",
          ],
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('preparation', CollectionType::class, [
        'label' => false,
        'help' => "preparation.help",
        'entry_type' => BloodFacetType::class,
        'entry_options' => [
          'attr' => [
            'data-bloodbather--bath-param' => "preparation",
            'data-controller' => 'bloodbather--bath',
            'data-form-collection-target' => 'block',
            'class' => "bdr p-2 rounded",
          ],
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'translation_domain' => 'blood-bather',
      'data_class' => BloodBath::class,
    ]);
  }
}
