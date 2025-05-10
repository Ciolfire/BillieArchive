<?php

namespace App\Form;

use App\Entity\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorForm;

class AttributeForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    /** @var Attribute $attribute */
    $attribute = $options['data'];

    $builder
      // ->add('identifier')
      // ->add('category')
      // ->add('type')
      ->add('name')
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $attribute->getDescription()
      ])
      ->add('fluff', RichTextEditorForm::class, ['data' => $attribute->getFluff()]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Attribute::class,
    ]);
  }
}
