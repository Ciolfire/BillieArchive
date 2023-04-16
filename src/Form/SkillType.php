<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class SkillType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Skill $skill */
    $skill = $options['data'];
    $converter = new LeagueMarkdown();
    $builder
      ->add('name', null, ['label' => 'name'])
      ->add('identifier', null, ['label' => 'identifier'])
      ->add('category', null, ['label' => 'category.label'])
      ->add('description', CKEditorType::class, ['label' => 'description.label', 'data' => $converter->convert($skill->getDescription())])
      ->add('fluff', CKEditorType::class, ['label' => 'description.fluff', 'data' => $converter->convert($skill->getFluff())]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Skill::class,
      'translation_domain' => 'app',
    ]);
  }
}
