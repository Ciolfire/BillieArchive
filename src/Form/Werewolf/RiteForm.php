<?php

namespace App\Form\Werewolf;

use App\Entity\Rite;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RiteForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;

    /** @var Rite $rite */
    $rite = $options['data'];

    $builder
      ->add('name')
      ->add('short')
      ->add('source', SourceableForm::class, [
        'data_class' => Rite::class,
        'label' => 'source.label',
        'translation_domain' => "book"
      ])
      ->add('details', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $rite->getDetails(),
      ])
      ->add('level', null, [])
      ->add('cost', null, [
        'label' => "cost",
        'translation_domain' => "app",
        'required' => false,
      ])
      ->add('action', ChoiceType::class, [
        'label' => "action.label.single",
        'help' => "help.action",
        'choices' => [
          'roll.action.instant' => 0,
          'roll.action.extended' => 1,
          'roll.action.reflexive' => 2,
        ],
        'translation_domain' => "app",
      ])
      ->add('isContested', null, [
        'label' => "roll.action.contested",
        'translation_domain' => "app",
      ])
      ->add('contestedText', null, [
        'label' => "roll.action.text",
        'translation_domain' => "app",
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Rite::class,
    ]);
  }
}
