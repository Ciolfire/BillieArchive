<?php declare(strict_types=1);

namespace App\Form\Vampire;

use App\Entity\Attribute;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Entity\Skill;
use App\Form\StatusEffectType;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Contracts\Translation\TranslatorInterface;


class DisciplinePowerType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    $translator = $this->translator;
    /** @var DisciplinePower */
    $power = $options['data'];
    /** @var Discipline */
    $discipline = $power->getDiscipline();

    $builder
      ->add('name', null, ['label' => 'name', 'translation_domain' => "app"])
      ->add('short', null, ['empty_data' => '', 'label' => 'short.label'])
      ->add('details', RichTextEditorForm::class, ['empty_data' => '', 'data' => $power->getDetails(), 'label' => false])
      ->add('level', null, ['label' => 'level', 'translation_domain' => "app"])
      ->add('source', SourceableForm::class, [
        'data_class' => DisciplinePower::class,
        'label' => 'source.label',
        'translation_domain' => "book",
      ])
      ->add('canToggle', null, ['label' => "toggle"])
      ->add('statusEffects', CollectionType::class, [
        'label' => false,
        'entry_type' => StatusEffectType::class,
        'entry_options' => [
          'label' => false, 
          'type' => 'vampire', 
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ]);
    ;
    if (!$discipline->isCoil() && !($discipline->isSorcery() && $power->getLevel() > 0)) {
      $builder
        // TODO parse the cost string ?
        // ->add('costs', TextType::class, [
        //   'mapped' => false,
        // ])
        ->add('attributes', null, [
          'label' => 'label.multi',
          'expanded' => true,
          'translation_domain' => "attribute",
          'group_by' => function ($choice) use ($translator) {
            /** @var Attribute $choice */
            return $translator->trans("category.{$choice->getCategory()}", [], 'app');
          },
        ])
        ->add('skills', null, [
          'label' => 'label.multi',
          'expanded' => true,
          'translation_domain' => "skill",
          'choice_attr' => ['class' => 'text-sub'],
          'group_by' => function ($choice) use ($translator) {
            /** @var Skill $choice */
            return $translator->trans("category.{$choice->getCategory()}", [], 'app');
          },
        ])
        ->add('usePotency', null, ['label' => "potency"])
        ->add('contestedText', null, ['label' => "contested.text", 'attr' => ['placeholder' => "contested.placeholder"]])
      ;
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => DisciplinePower::class,
      'translation_domain' => "discipline",
    ]);
  }
}
