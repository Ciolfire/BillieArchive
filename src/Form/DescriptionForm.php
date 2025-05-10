<?php

namespace App\Form;

use App\Entity\Description;
use App\Form\Type\ContentTypeForm;
use App\Form\Type\SourceableForm;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RichTextEditorForm;
use Symfony\Contracts\Translation\TranslatorInterface;


class DescriptionForm extends AbstractType
{  
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Description */
    $element = $options['data'];

    $builder
      ->add('name', null, ['label' => 'title'])
      ->add('value', RichTextEditorForm::class, ['empty_data' => '', 'data' => $element->getValue(), 'label' => false])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Description::class,
      'translation_domain' => 'app',
    ]);
  }
}
