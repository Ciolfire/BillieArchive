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
    /** @var Merit $merit */
    $merit = $options['data'];
    $converter = new LeagueMarkdown();

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
      // ->add('prerequisites', CollectionType::class, [
      //   // each entry in the array will be an "email" field
      //   'entry_type' => CollectionType::class,
      //   // these options are passed to each "email" type
      //   'entry_options' => [
      //       'attr' => ['class' => 'prerequisite-box'],
      //   ],])
      ->add('effect', CKEditorType::class, ['data' => $converter->convert($merit->getEffect())])
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
