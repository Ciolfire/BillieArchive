<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterInfoAccessType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $data = $options['data'];
    $builder
      ->add('infos', CollectionType::class, [
        'label' => false,
        'entry_type' => CharacterInfoType::class,
        'entry_options' => ['character' => $data, 'path' => $options['path']],
        'allow_add' => true,
        'allow_delete' => true
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'translation_domain' => 'app',
      'path' => null,
    ]);
  }
}
