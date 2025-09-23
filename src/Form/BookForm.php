<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Types\SettingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Book $book */
    $book = $options['data'];
    
    $description = $book->getDescription();

    $builder
      ->add('name', null, ['label' => "title", "translation_domain" => "app"])
      ->add('short', null, ['label' => "shortname"])
      ->add('ruleset', ChoiceType::class, [
        'label' => "ruleset.label",
        'choices' => [
          "ruleset.first" => 1,
          "ruleset.second" => 2
        ],
        'translation_domain' => 'rule',
      ])
      ->add('setting', ChoiceType::class, [
        'label' => 'label',
        'translation_domain' => 'setting',
        'choices' => get_class_vars(SettingType::class),
        'choice_label' => function ($key) {
          return "{$key}.label";
        },
        'choice_translation_domain' => 'setting',
      ])
      ->add('type', null, ['label' => "category.label.single", "translation_domain" => "app"])
      ->add('description', RichTextEditorForm::class, ['empty_data' => '', 'data' => $description, 'label' => false])
      ->add('releasedAt', DateType::class, [
        'label' => 'release',
        'input' => 'datetime_immutable',
        'years' => range('2004', date('Y')),
      ])
      ->add('cover', FileType::class, [
        'label' => 'cover',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File([
            'mimeTypes' => [
              'image/*',
            ],
            'mimeTypesMessage' => 'image.invalid',
          ])
        ],
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Book::class,
      "translation_domain" => 'book',
    ]);
  }
}
