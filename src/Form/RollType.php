<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Derangement;
use App\Entity\Roll;
use App\Entity\Skill;
use App\Form\Type\SourceableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RichTextEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Contracts\Translation\TranslatorInterface;


class RollType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    $translator = $this->translator;

    /** @var Derangement */
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
      ->add('details', RichTextEditorType::class, ['empty_data' => '', 'data' => $element->getDetails(), 'label' => false])
      ->add('isContested', null, ['label' => "contested.label"])
      ->add('contestedText', null, ['label' => "contested.text"])
      ->add('isImportant', null, ['label' => "important"])
      ->add('type', ChoiceType::class, [
        'label' => 'type.label',
        'required' => false,
        'choices' => [
          'type.vampire' => 'vampire',
        ],
      ])
      ->add('attributes', null, [
        'label' => 'label.single',
        'expanded' => true,
        'translation_domain' => "attribute",
        'group_by' => function ($choice) use ($translator) {
          /** @var Attribute $choice */
          return $translator->trans($choice->getCategory(), [], 'character');
        },
      ])
      ->add('skills', null, [
        'label' => 'label.multi',
        'expanded' => true,
        'translation_domain' => "skill",
        'choice_attr' => ['class' => 'text-sub'],
        'group_by' => function ($choice) use ($translator) {
          /** @var Skill $choice */
          return $translator->trans($choice->getCategory(), [], 'character');
        },
      ])
      ->add('source', SourceableType::class, [
        'data_class' => Roll::class,
        'label' => 'source.label',
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Roll::class,
      'translation_domain' => "app",
    ]);
  }
}
