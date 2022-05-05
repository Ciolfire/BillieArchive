<?php

namespace App\Form;

use App\Entity\Merit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Twig\Extra\Markdown\LeagueMarkdown;

class MeritType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Merit $merit */
    $merit = $options['data'];
    $converter = new LeagueMarkdown();
    
    // $converter = new HtmlConverter();

    $builder
      ->add('name')
      ->add('description')
      ->add('type', null, ['required' => false, 'empty_data' => ''])
      ->add('category')
      ->add('isFighting')
      ->add('isExpanded')
      ->add('min')
      ->add('max')
      ->add('isUnique')
      ->add('isCreationOnly')
      // ->add('prerequisites', CollectionType::class, [
      //   // each entry in the array will be an "email" field
      //   'entry_type' => CollectionType::class,
      //   // these options are passed to each "email" type
      //   'entry_options' => [
      //       'attr' => ['class' => 'prerequisite-box'],
      //   ],])
      ->add('effect', CKEditorType::class, ['data' => $converter->convert($merit->getEffect())])
      ->add('book');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Merit::class,
    ]);
  }
}
