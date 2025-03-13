<?php

namespace App\Form\Mage;

use App\Entity\MageSpell;
use App\Form\Type\RichTextEditorType;
use App\Form\Type\SourceableType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MageSpellType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;

    $builder
    ->add('arcanum', null, [
      'label' => "label.single",
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
      },
    ])
      ->add('name', null, [
        'label' => "title",
        'help' => "help.title",
      ])
      ->add('short', null, [
        'label' => "short",
        'help' => "help.short",
        'translation_domain' => "app",
      ])
      ->add('description', RichTextEditorType::class, [
        'label' => false,
        'help' => "help.description",
        'empty_data' => "",
      ])
      ->add('practice', null, [
        'label' => "practice",
        'help' => "help.practice",
        'choice_label' => "name",
        'group_by' => "level",
      ]
      )
      ->add('level', null, [
        'label' => "level",
        'help' => "help.level",
        'required' => false,
        'empty_data' => '0',
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
      ->add('duration', null, [
        'label' => "duration",
        'help' => "help.duration",
      ])
      ->add('isVulgar', null, [
        'label' => "aspect.choice",
        'help' => "help.aspect",
      ])
      ->add('cost', null, [
        'label' => "cost",
        'help' => "help.cost",
      ])
      ->add('rules', RichTextEditorType::class, [
        'empty_data' => '',
        'label' => false,
        'help' => "help.rules",
      ])
      ->add('skill', null, [
        'required' => true,
        'label' => "label.single",
        'translation_domain' => "skill",
        'choice_attr' => ['class' => 'text-sub'],
        'group_by' => function ($choice) use ($translator) {
          /** @var Skill $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('source', SourceableType::class, [
        'data_class' => MageSpell::class,
        'label' => "source.label",
        'translation_domain' => "book",
        ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => MageSpell::class,
      'translation_domain' => "spell",
    ]);
  }
}
