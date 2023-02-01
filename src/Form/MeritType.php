<?php

namespace App\Form;

use App\Entity\Merit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class MeritType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    /** @var Merit $merit */
    $merit = $options['data'];

    $builder
      ->add('name')
      ->add('type', null, ['required' => false, 'empty_data' => ''])
      ->add('category', ChoiceType::class, [
        'choices' => [
          'mental' => 'mental',
          'physical' => 'physical',
          'social' => 'social',
        ],
        'translation_domain' => 'character',
        ])
      ->add('description')
      ->add('min')
      ->add('max')
      ->add('isCreationOnly')
      ->add('isFighting')
      ->add('isExpanded')
      ->add('isUnique')
      ->add('effect', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($merit->getEffect())])
      ->add('prereqs', CollectionType::class, [
        'label' => false,
        'entry_type' => PrerequisiteType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
      ])
      ->add('book')
      ->add('page');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Merit::class,
    ]);
  }
}
