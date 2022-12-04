<?php

namespace App\Form;

use App\Entity\DisciplinePower;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Twig\Extra\Markdown\LeagueMarkdown;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class DisciplinePowerType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    /** @var DisciplinePower */
    $power = $options['data'];
    
    $builder
      ->add('name', null, ['label' => 'name', 'translation_domain' => "app"])
      ->add('short', null, ['empty_data' => '', 'label' => 'short', 'translation_domain' => "app"])
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($power->getDetails()), 'label' => false])
      ->add('level', null, ['label' => 'level', 'translation_domain' => "app"])
      ->add('discipline', null, ['label' => 'discipline.label'])
      ->add('attribute', null, ['label' => 'attribute.label', 'translation_domain' => "character"])
      ->add('skill', null, ['label' => 'skill.label', 'translation_domain' => "character"])
      ->add('save', SubmitType::class, ['label' => 'save', 'translation_domain' => "app"]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => DisciplinePower::class,
      'translation_domain' => "vampire",
    ]);
  }
}
