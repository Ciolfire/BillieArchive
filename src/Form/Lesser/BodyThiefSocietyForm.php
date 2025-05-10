<?php

namespace App\Form\Lesser;

use App\Entity\BodyThiefSociety;
use App\Entity\Types\BodyThiefTalent;
use Doctrine\ORM\EntityRepository;
use App\Form\Type\RichTextEditorType;
use App\Form\Type\SourceableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BodyThiefSocietyForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', null, [
        'label' => 'name',
        'translation_domain' => 'app',
        ])
      ->add('talentType', EnumType::class, [
        'class' => BodyThiefTalent::class,
        'required' => false,
        'label' => 'talent.label',
        'choice_label' => function ($choice) {
          return "talent.{$choice->name}";
        }
      ])
      ->add('advantage', RichTextEditorType::class, [
        'label' => 'society.advantage.label',
        'help' => 'society.advantage.help',
        'empty_data' => "",
      ])
      ->add('weakness', RichTextEditorType::class, [
        'label' => 'society.weakness.label',
        'help' => 'society.weakness.help',
        'empty_data' => "",
      ])
      ->add('description', RichTextEditorType::class, [
        'label' => 'description',
        'translation_domain' => 'app',
        'empty_data' => "",
      ])
      ->add('creation', RichTextEditorType::class, [
        'label' => 'society.creation',
        'empty_data' => "",
      ])
      ->add('definingMerit', null, [
        'label' => 'society.merit.defining',
        'help' => 'society.merit.help',
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'required' => false,
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->queryfindByType('body_thief');
        },
      ])
      ->add('source', SourceableType::class, [
        'data_class' => BodyThiefSocietyForm::class,
        'label' => 'source.label',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => BodyThiefSociety::class,
      'translation_domain' => 'body-thief',
    ]);
  }
}
