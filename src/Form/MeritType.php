<?php declare(strict_types=1);

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
      ->add('name', null, ['label' => "name"])
      ->add('type', null, ['label' => "type.label", 'required' => false, 'empty_data' => ''])
      ->add('category', ChoiceType::class, [
        'label' => "category.label",
        'choices' => [
          'category.mental' => 'mental',
          'category.physical' => 'physical',
          'category.social' => 'social',
        ],
      ])
      ->add('description', null, ['label' => "description.label"])
      ->add('effect', CKEditorType::class, ['label' => "effect", 'empty_data' => '', 'data' => $converter->convert($merit->getEffect())])
      ->add('min', null, ['label' => "min"])
      ->add('max', null, ['label' => "max"])
      ->add('isCreationOnly', null, ['label' => "merit.creation"])
      ->add('isUnique', null, ['label' => "merit.unique"])
      ->add('isExpanded', null, ['label' => "merit.expanded"])
      ->add('isFighting', null, ['label' => "merit.fighting"])
      ->add('prerequisites', CollectionType::class, [
        'label' => false,
        'entry_type' => PrerequisiteType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('book', null, ['label' => "book"])
      ->add('page', null, ['label' => "page"])
      ->add('homebrewFor', null, ['label' => "homebrewFor"])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Merit::class,
      'translation_domain' => 'app',
    ]);
  }
}
