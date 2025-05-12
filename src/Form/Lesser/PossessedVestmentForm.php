<?php

namespace App\Form\Lesser;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\DisciplinePower;
use App\Entity\PossessedVestment;
use App\Entity\Vice;
use App\Form\StatusEffectForm;
use App\Form\Type\RadiobuttonType;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Contracts\Translation\TranslatorInterface;

class PossessedVestmentForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;
    /** @var PossessedVestment */
    $vestment = $options['data'];

    $builder
    ->add('vice', EntityType::class, [
      'class' => Vice::class,
      'label' => 'vice.label.single',
      'translation_domain' => "character",
    ])
    ->add('level', RadiobuttonType::class, [
      // 'data' => $vestment->getLevel(),
      'choices' => [1,2,3],
      'choice_label' => function (mixed $value) {
        return $value;
      },
      'label' => 'level',
      'translation_domain' => "app",
    ])
      ->add('name', null, ['label' => 'name', 'translation_domain' => "app"])
      ->add('effect', RichTextEditorForm::class, ['empty_data' => '', 'data' => $vestment->getEffect(), 'label' => false])
      ->add('source', SourceableForm::class, [
        'data_class' => DisciplinePower::class,
        'label' => 'source.label',
        'translation_domain' => "book",
      ])
      ->add('canToggle', null, ['label' => "toggle", 'translation_domain' => "app"])
      ->add('statusEffects', CollectionType::class, [
        'label' => false,
        'entry_type' => StatusEffectForm::class,
        'entry_options' => [
          'label' => false, 
          'type' => 'possessed', 
        ],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => PossessedVestment::class,
    ]);
  }
}
