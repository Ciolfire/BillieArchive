<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorType;


class SkillType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Skill $skill */
    $skill = $options['data'];
    
    $builder
      ->add('name', null, ['label' => 'name'])
      ->add('identifier', null, ['label' => 'identifier'])
      ->add('category', null, ['label' => 'category.label'])
      ->add('description', RichTextEditorType::class, ['label' => 'description.label', 'data' => $skill->getDescription()])
      ->add('fluff', RichTextEditorType::class, ['label' => 'description.fluff', 'data' => $skill->getFluff()]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Skill::class,
      'translation_domain' => 'app',
    ]);
  }
}
