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
      ->add('name', null, [
        'label' => "name",
        'translation_domain' => "app",
      ])
      ->add('arcanum', null, [
        'label' => "label.single",
        'translation_domain' => "arcanum",
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('short', null, [
        'label' => "short",
        'translation_domain' => "app",
      ])
      ->add('description', RichTextEditorType::class, [
        'label' => "description",
        'translation_domain' => "app",
        'empty_data' => "",
      ])
      ->add('practice', null, [
        'label' => "practice",
        'choice_label' => "name",
        'group_by' => "level",
      ]
      )
      ->add('action', ChoiceType::class, [
        'label' => "action.label.single",
        'choices' => [
          'roll.action.instant' => 0,
          'roll.action.extended' => 1,
          'roll.action.reflexive' => 2,
        ],
        'translation_domain' => "app",
      ])
      ->add('isVulgar', null, [
        'label' => "vulgar",
      ])
      ->add('cost', null, [
        'label' => "cost",
        'translation_domain' => "app",
        'empty_data' => "cost.none",
      ])
      ->add('rules', RichTextEditorType::class, [
        'empty_data' => '',
        'label' => "rules",
        'translation_domain' => "app",
      ])
      ->add('skill', null, [
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
