<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VampireRulesForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $rules = $options['ruleset'];
    $builder
      ->add('maxVitae', CollectionType::class, [
        'label' => false,
        'entry_type' => IntegerType::class,
        'data' => $rules['maxVitae'],
        'translation_domain' => false,
      ])
      ->add('maxVitaePerTurn', CollectionType::class, [
          'label' => false,
          'entry_type' => IntegerType::class,
          'data' => $rules['maxVitaePerTurn'],
          'translation_domain' => false,
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'ruleset' => [],
      'disabled' => true,
    ]);
  }
}
