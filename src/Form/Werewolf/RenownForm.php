<?php

namespace App\Form\Werewolf;

use App\Entity\Renown;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RenownForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Renown */
    $renown = $options['data'];

    $builder
      ->add('name')
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $renown->getDescription(),
        'label' => "description",
        'translation_domain' => 'app',
      ])
      ->add('source', SourceableForm::class, [
        'data_class' => Renown::class,
        'label' => 'source.label',
        'translation_domain' => "book",
        'isHomebrewable' => false,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Renown::class,
    ]);
  }
}
