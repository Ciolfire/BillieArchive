<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LesserTemplateType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('template', ChoiceType::class, [
        'label' => 'label.single',
        'choices' => $options['templates'],
        'expanded' => true,
        'translation_domain' => 'content-type',
        'attr' => [
          "data-action" => "change->character--lesser-template#loadForm",
        ]
      ])
      ->add('submit', SubmitType::class, ['label' => 'action.apply'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'translation_domain' => 'app',
      'templates' => [],
    ]);
  }
}
