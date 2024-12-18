<?php

namespace App\Form;

use App\Entity\Virtue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorType;


class VirtueType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Virtue $virtue */
    $virtue = $options['data'];
    
    $builder
      ->add('name', null, ['label' => 'name'])
      ->add('details', RichTextEditorType::class, ['label' => 'description.fluff', 'data' => $virtue->getDetails()]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Virtue::class,
      'translation_domain' => 'app',
    ]);
  }
}
