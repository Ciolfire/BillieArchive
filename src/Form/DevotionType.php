<?php

namespace App\Form;

use App\Entity\Devotion;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Extra\Markdown\LeagueMarkdown;

class DevotionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    /** @var Devotion $devotion */
    $devotion = $options['data'];

    $builder
      ->add('name')
      ->add('cost')
      ->add('description', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($devotion->getDescription())])
      ->add('short')
      ->add('page')
      ->add('prerequisites', CollectionType::class, [
        'label' => false,
        'entry_type' => PrerequisiteType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
      ])
      ->add('homebrewFor')
      ->add('book');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Devotion::class,
    ]);
  }
}
