<?php

namespace App\Form;

use App\Entity\Vice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorForm;


class ViceForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Vice $vice */
    $vice = $options['data'];
    
    $builder
      ->add('name', null, ['label' => 'name'])
      ->add('details', RichTextEditorForm::class, ['label' => 'description.fluff', 'data' => $vice->getDetails()]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Vice::class,
      'translation_domain' => 'app',
    ]);
  }
}
