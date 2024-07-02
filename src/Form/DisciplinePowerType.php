<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Entity\Skill;
use App\Form\Type\SourceableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Twig\Extra\Markdown\LeagueMarkdown;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
    $converter = new LeagueMarkdown();
    $translator = $this->translator;
    /** @var DisciplinePower */
    $power = $options['data'];
    /** @var Discipline */
    $discipline = $power->getDiscipline();

    $builder
      ->add('name', null, ['label' => 'name', 'translation_domain' => "app"])
      ->add('short', null, ['empty_data' => '', 'label' => 'short', 'translation_domain' => "app"])
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($power->getDetails()), 'label' => false])
      ->add('level', null, ['label' => 'level', 'translation_domain' => "app"])
      ->add('source', SourceableType::class, [
        'data_class' => DisciplinePower::class,
        'label' => 'source',
      ]);
    if (!($discipline->isSorcery() && $power->getLevel() > 0) && !$discipline->isCoil()) {
      $builder
        ->add('attributes', null, [
          'label' => 'attributes.label',
          'expanded' => true,
          'translation_domain' => "character",
          'group_by' => function ($choice) use ($translator) {
            /** @var Attribute $choice */
            return $translator->trans($choice->getCategory(), [], 'character');
          },
        ])
        ->add('skills', null, [
          'label' => 'skills.label',
          'expanded' => true,
          'translation_domain' => "character",
          'choice_attr' => ['class' => 'text-sub'],
          'group_by' => function ($choice) use ($translator) {
            /** @var Skill $choice */
            return $translator->trans($choice->getCategory(), [], 'character');
          },
        ])
        ->add('contestedText')
        ;
    }
    $builder->add('save', SubmitType::class, ['label' => 'save', 'translation_domain' => "app"]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => DisciplinePower::class,
      'translation_domain' => "vampire",
    ]);
  }
}
