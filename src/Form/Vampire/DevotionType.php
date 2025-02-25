<?php declare(strict_types=1);

namespace App\Form\Vampire;

use App\Entity\Attribute;
use App\Entity\Devotion;
use App\Entity\Skill;
use App\Form\PrerequisiteType;
use App\Form\Type\SourceableType;
use Doctrine\ORM\EntityRepository;
use App\Form\Type\RichTextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class DevotionType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    /** @var Devotion $devotion */
    $devotion = $options['data'];
    $builder
      ->add('name', null, ['label' => 'name', 'translation_domain' => 'app'])
      ->add('cost', null, ['label' => 'cost', 'translation_domain' => 'app'])
      ->add('description', RichTextEditorType::class, ['label' => false, 'empty_data' => '', 'data' => $devotion->getDescription()])
      ->add('short', null, ['label' => 'short'])
      ->add('bloodline', null, ['label' => 'bloodline.label.single', 'translation_domain' => 'clan'])
      ->add('prerequisites', CollectionType::class, [
        'label' => false,
        'entry_type' => PrerequisiteType::class,
        'entry_options' => [
          'label' => false, 
          'type' => 'devotion', 
          'homebrew' => !is_null($devotion->getHomebrewFor()) ? $devotion->getHomebrewFor()->getId() : null,
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('disciplines', null, [
        'label' => false,
        'expanded' => true,
        'translation_domain' => "vampire",
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('attributes', null, [
        'label' => 'label.multi',
        'expanded' => true,
        'translation_domain' => "attribute",
        'group_by' => function($choice) {
          /** @var Attribute $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('skills', null, [
        'label' => 'label.multi',
        'expanded' => true,
        'translation_domain' => "skill",
        'choice_attr' => ['class' =>'text-sub'],
        'group_by' => function($choice) {
          /** @var Skill $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('contestedText', null, ['label' => 'contested.text', 'attr' => ['placeholder' => 'contested.placeholder']])
      ->add('usePotency', null, ['label' => 'potency'])
      ->add('source', SourceableType::class, [
        'data_class' => Devotion::class,
        'label' => 'source.label',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Devotion::class,
      'translation_domain' => 'discipline',
    ]);
  }
}
