<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\ContentType;
use App\Entity\Flaw;
use App\Form\Type\ContentTypeForm;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlawForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;

    /** @var Flaw */
    $element = $options['data'];

    $builder
      ->add('name')
      ->add('category', ChoiceType::class, [
        'label' => "category.label.single",
        'translation_domain' => 'app',
        'required' => false,
        'choices' => [
          'category.mental' => 'mental',
          'category.physical' => 'physical',
          'category.social' => 'social',
          'category.mystical' => 'mystical',
        ],
      ])
      ->add('effect', RichTextEditorForm::class, ['empty_data' => '', 'data' => $element->getEffect(), 'label' => false])
      ->add('type', EntityType::class, [
        'class' => ContentType::class,
        'choice_label' => 'id',
      ])
      ->add('source', SourceableForm::class, [
        'data_class' => Flaw::class,
        'label' => 'source.label',
      ])
      ->add('type', ContentTypeForm::class, [
        'data_class' => Flaw::class,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Flaw::class,
    ]);
  }
}
