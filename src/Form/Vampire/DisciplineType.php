<?php declare(strict_types=1);

namespace App\Form\Vampire;

use App\Entity\Discipline;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorForm;

class DisciplineType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {

    $builder
      ->add('name', null, ['label' => 'name', 'translation_domain' => 'app'])
      ->add('short', null, ['label' => 'short.label', 'help' => 'short.help'])
      ->add('description', null, ['label' => 'description', 'translation_domain' => 'app'])
      ->add('rules', RichTextEditorForm::class, ['label' => 'rules', 'empty_data' => ''])
      ->add('isRestricted', null, ['label' => 'restricted', 'help' => 'help.restricted'])
      ->add('source', SourceableForm::class, [
        'data_class' => Discipline::class,
        'label' => 'source.label',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Discipline::class,
      'translation_domain' => 'discipline',
    ]);
  }
}
