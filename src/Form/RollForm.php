<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Roll;
use App\Entity\Skill;
use App\Form\Type\ContentTypeForm;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Contracts\Translation\TranslatorInterface;


class RollForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    $translator = $this->translator;

    /** @var Roll */
    $element = $options['data'];

    $builder
      ->add('name', null, ['label' => "name"])
      ->add('action', ChoiceType::class, [
        'label' => 'action.label.single',
        'choices' => [
          'roll.action.instant' => 0,
          'roll.action.extended' => 1,
          'roll.action.reflexive' => 2,
        ]
      ])
      ->add('details', RichTextEditorForm::class, ['empty_data' => '', 'data' => $element->getDetails(), 'label' => false])
      ->add('isContested', null, ['label' => "roll.action.contested"])
      ->add('contestedText', null, ['label' => "roll.action.text"])
      ->add('isImportant', null, ['label' => "important"])
      ->add('type', ContentTypeForm::class, [
        'data_class' => Roll::class,
        'label' => false,
      ])
      ->add('attributes', null, [
        'label' => 'label.single',
        'expanded' => true,
        'translation_domain' => "attribute",
        'group_by' => function ($choice) use ($translator) {
          /** @var Attribute $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('skills', null, [
        'label' => 'label.multi',
        'expanded' => true,
        'translation_domain' => "skill",
        'choice_attr' => ['class' => 'text-sub'],
        'group_by' => function ($choice) use ($translator) {
          /** @var Skill $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('source', SourceableForm::class, [
        'data_class' => Roll::class,
        'label' => 'source.label',
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Roll::class,
      'data' => new Roll(),
      'translation_domain' => "app",
    ]);
  }
}
