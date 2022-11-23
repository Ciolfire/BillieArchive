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
      ->add('name')
      ->add('ruleset')
      ->add('type')
      ->add('short')
      ->add('description', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($description), 'label' => false])
      ->add('releasedAt', DateType::class, [
        'input'  => 'datetime_immutable',
        'years' => range('2004', date('Y')),
      ])
      ->add('setting')
      ->add('cover', FileType::class, [
        'label' => 'file',
        'translation_domain' => 'app',
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
    ]);
  }
}
