<?php

namespace App\Form\Werewolf;

use App\Entity\Attribute;
use App\Entity\Gift;
use App\Entity\GiftList;
use App\Entity\Renown;
use App\Entity\Skill;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class GiftForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;

    /** @var Gift $gift */
    $gift = $options['data'];

    $builder
      ->add('name')
      ->add('short')
      ->add('source', SourceableForm::class, [
        'data_class' => GiftList::class,
        'label' => 'source.label',
        'translation_domain' => "book"
      ])
      ->add('details', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $gift->getDetails(),
      ])
      ->add('level', null, [])
      ->add('cost', null, [
        'label' => "cost",
        'translation_domain' => "app",
      ])
      ->add('attribute', EntityType::class, [
        'class' => Attribute::class,
        'required' => false,
        'label' => 'label.multi',
        'translation_domain' => "attribute",
        'group_by' => function ($choice) use ($translator) {
          /** @var Attribute $choice */
          return $translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('skill', EntityType::class, [
        'class' => Skill::class,
        'required' => false,
        'label' => 'label.multi',
        'translation_domain' => "skill",
        'choice_attr' => ['class' => 'text-sub'],
        'group_by' => function ($choice) use ($translator) {
          /** @var Skill $choice */
          return $translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('renown', EntityType::class, [
        'class' => Renown::class,
        'required' => false,
        'choice_label' => 'name',
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
      'data_class' => Gift::class,
    ]);
  }
}
