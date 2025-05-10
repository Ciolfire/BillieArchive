<?php

namespace App\Form\Lesser;

use Doctrine\ORM\EntityRepository;
use App\Entity\ThaumaturgeTradition;
use App\Form\Type\RichTextEditorType;
use App\Form\Type\SourceableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThaumaturgeTraditionForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', null, [
        'label' => 'name',
        'translation_domain' => 'app',
        ])
      ->add('quote', null, [
        'label' => 'quote',
        'translation_domain' => 'app',
        ])
      ->add('strengths', RichTextEditorType::class, [
        'label' => 'tradition.strengths',
        'help' => 'tradition.help.strengths',
        'empty_data' => "",
      ])
      ->add('weaknesses', RichTextEditorType::class, [
        'label' => 'tradition.weaknesses',
        'help' => 'tradition.help.weaknesses',
        'empty_data' => "",
      ])
      ->add('description', RichTextEditorType::class, [
        'label' => 'description',
        'translation_domain' => 'app',
        'empty_data' => "",
      ])
      ->add('creation', RichTextEditorType::class, [
        'label' => 'tradition.creation',
        'empty_data' => "",
      ])
      ->add('definingMerit', null, [
        'label' => 'tradition.merit.defining',
        'help' => 'tradition.help.merit.defining',
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->queryfindByType('thaumaturge');
        },
      ])
      ->add('pathMerits', null, [
        'label' => 'tradition.merit.path',
        'help' => 'tradition.help.merit.path',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->queryfindByType('thaumaturge');
        },
      ])
      ->add('source', SourceableType::class, [
        'data_class' => ThaumaturgeTraditionForm::class,
        'label' => 'source.label',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ThaumaturgeTradition::class,
      'translation_domain' => 'thaumaturge',
    ]);
  }
}
