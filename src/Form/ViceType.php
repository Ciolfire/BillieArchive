<?php

namespace App\Form;

use App\Entity\Vice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class ViceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Vice $vice */
    $vice = $options['data'];
    $converter = new LeagueMarkdown();
    $builder
      ->add('name', null, ['label' => 'name'])
      ->add('details', CKEditorType::class, ['label' => 'description.fluff', 'data' => $converter->convert($vice->getDetails())]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Vice::class,
      'translation_domain' => 'app',
    ]);
  }
}
