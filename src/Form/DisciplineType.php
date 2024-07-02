<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Discipline;
use App\Form\Type\SourceableType;
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
      ->add('name', null, ['label' => 'name'])
      ->add('short', null, ['label' => 'short', 'help' => 'help.short'])
      ->add('description', null, ['label' => 'description'])
      ->add('rules', CKEditorType::class, ['label' => 'rules', 'empty_data' => '', 'data' => $converter->convert($rules)])
      ->add('isRestricted', null, ['label' => 'restricted', 'help' => 'help.restricted'])
      ->add('source', SourceableType::class, [
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
