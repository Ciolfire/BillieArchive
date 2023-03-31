<?php

namespace App\Form;

use App\Entity\Discipline;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class DisciplineType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    /** @var Discipline $discipline */
    $discipline = $options['data'];
    $rules = $discipline->getRules();
    if (is_null($rules)) {
      $rules = "";
    }

    $builder
      ->add('name')
      ->add('homebrewFor')
      ->add('description')
      ->add('short')
      ->add('rules', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($rules)])
      ->add('isRestricted')
      ->add('book')
      ->add('page');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Discipline::class,
    ]);
  }
}
