<?php

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
    /** @var Skill $attribute */
    $skill = $options['data'];
    $converter = new LeagueMarkdown();
    $builder
      // ->add('identifier')
      // ->add('category')
      ->add('name')
      ->add('description', CKEditorType::class, ['data' => $converter->convert($skill->getDescription())])
      ->add('fluff', CKEditorType::class, ['data' => $converter->convert($skill->getFluff())]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Skill::class,
    ]);
  }
}
