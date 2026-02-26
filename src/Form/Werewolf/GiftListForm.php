<?php

namespace App\Form\Werewolf;

use App\Entity\GiftList;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GiftListForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var GiftList */
    $list = $options['data'];

    $builder
      ->add('name')
      ->add('source', SourceableForm::class, [
        'data_class' => GiftList::class,
        'label' => 'source.label',
        'translation_domain' => "book"
      ])
      ->add('short')
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $list->getDescription(),
      ])
      ->add('rules', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $list->getRules(),
      ])
      ->add('isCommon')
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => GiftList::class,
    ]);
  }
}
