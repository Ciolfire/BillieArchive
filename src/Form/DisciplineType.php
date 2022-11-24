<?php

namespace App\Form;

use App\Entity\Discipline;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class DisciplineType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Discipline $discipline */
    $discipline = $options['data'];
    $converter = new LeagueMarkdown();

    $builder
      ->add('name')
      ->add('homebrewFor')
      ->add('description')
      ->add('short')
      ->add('rules', CKEditorType::class, ['data' => $converter->convert($discipline->getRules())])
      ->add('isRestricted')
      ->add('book')
      ->add('page')
      ->add('save', SubmitType::class);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Discipline::class,
    ]);
  }
}
