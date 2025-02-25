<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Merit;
use App\Entity\Roll;
use App\Form\Type\ContentTypeType;
use App\Form\Type\SourceableType;
use App\Form\Type\RichTextEditorType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class MeritType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    /** @var Merit $merit */
    $merit = $options['data'];

    $builder
      ->add('name', null, ['label' => "label.single"])
      ->add('min', null, ['label' => "min"])
      ->add('max', null, ['label' => "max"])
      ->add('source', SourceableType::class, [
        'data_class' => Merit::class,
        'label' => 'source.label',
      ])
      ->add('type', ContentTypeType::class, [
        'data_class' => Merit::class,
      ])
      ->add('category', ChoiceType::class, [
        'label' => "category.label.single",
        'translation_domain' => 'app',
        'required' => false,
        'choices' => [
          'category.mental' => 'mental',
          'category.physical' => 'physical',
          'category.social' => 'social',
          'category.location' => 'location',
        ],
      ])
      ->add('description', null, ['label' => 'description', 'help' => 'help.description', 'required' => true, 'empty_data' => ""])
      ->add('effect', RichTextEditorType::class, ['label' => "effect", 'empty_data' => '', 'data' => $merit->getEffect()])
      ->add('roll', RollableType::class, ['required' => false])
      ->add('isCreationOnly', null, ['label' => "creation", 'help' => "help.creation"])
      ->add('isUnique', null, ['label' => "unique", 'help' => "help.unique"])
      ->add('isExpanded', null, ['label' => "expanded", 'help' => "help.expanded"])
      ->add('isFighting', null, ['label' => "fighting", 'help' => "help.fighting"])
      ->add('isRelation', null, ['label' => "relation", 'help' => "help.relation"])
      ->add('prerequisites', CollectionType::class, [
        'label' => false,
        'entry_type' => PrerequisiteType::class,
        'entry_options' => [
          'label' => false,
          'type' => 'merit',
          'homebrew' => !is_null($merit->getHomebrewFor()) ? $merit->getHomebrewFor()->getId() : null,
        ],
        'allow_add' => true,
        'allow_delete' => true,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Merit::class,
      'translation_domain' => 'merit',
    ]);
  }
}
