<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Twig\Extra\Markdown\LeagueMarkdown;

class BookType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Book $book */
    $book = $options['data'];
    $converter = new LeagueMarkdown();
    $description = $book->getDescription();

    $builder
      ->add('name', null, ['label' => "name"])
      ->add('ruleset', ChoiceType::class, [
        'label' => "ruleset.label",
        'choices' => [
          "ruleset.first" => 1,
          "ruleset.second" => 2
        ],
      ])
      ->add('type', null, ['label' => "type.label"])
      ->add('short', null, ['label' => "shortname"])
      ->add('description', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($description), 'label' => false])
      ->add('releasedAt', DateType::class, [
        'label' => 'release',
        'input' => 'datetime_immutable',
        'years' => range('2004', date('Y')),
      ])
      ->add('setting', null, ['label' => "setting"])
      ->add('cover', FileType::class, [
        'label' => 'cover',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File([
            'mimeTypes' => [
              'image/*',
            ],
            'mimeTypesMessage' => 'image Invalid',
          ])
        ],
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Book::class,
      "translation_domain" => 'app',
    ]);
  }
}
