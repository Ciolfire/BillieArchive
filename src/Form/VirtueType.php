<?php

namespace App\Form;

use App\Entity\Virtue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class VirtueType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Virtue $virtue */
    $virtue = $options['data'];
    $converter = new LeagueMarkdown();
    $builder
      ->add('name', null, ['label' => 'name'])
      ->add('details', CKEditorType::class, ['label' => 'description.fluff', 'data' => $converter->convert($virtue->getDetails())]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Virtue::class,
      'translation_domain' => 'app',
    ]);
  }
}
