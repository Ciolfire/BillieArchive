<?php

namespace App\Form;

use App\Entity\Items\Equipment;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EquipmentForm extends ItemForm
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);
    
    $item = $options['data'];

    $builder
      ->add('quality', ChoiceType::class, [
        'label' => 'quality.label',
        'help' => 'help.quality',
        'choices' => [
            'quality.normal' => array_combine(range(0, 5), range(0, 5)),
            'quality.surnatural' => array_combine(range(6, 10), range(6, 10)),
        ],
      ])
      ->add('functionality', RichTextEditorForm::class, [
        'label' => 'functionality.label',
        'empty_data' => '',
        'data' => $item->getFunctionality(),
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Equipment::class,
      'translation_domain' => 'item',
    ]);
  }
}
