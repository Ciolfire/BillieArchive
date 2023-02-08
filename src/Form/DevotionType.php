<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Devotion;
use App\Entity\Skill;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extra\Markdown\LeagueMarkdown;

class DevotionType extends AbstractType
{
  public $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    $translator = $this->translator;
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
      ->add('disciplines', null, [
        'label' => 'disciplines.label',
        'expanded' => false,
        'translation_domain' => "vampire",
      ])
      ->add('attributes', null, [
        'label' => 'attributes.label',
        'expanded' => true,
        'translation_domain' => "character",
        'group_by' => function($choice) use ($translator) {
          /** @var Attribute $choice */
          return $translator->trans($choice->getCategory(), [], 'character');
        },
      ])
      ->add('skills', null, [
        'label' => 'skills.label',
        'expanded' => true,
        'translation_domain' => "character",
        'choice_attr' => ['class' =>'text-sub'],
        'group_by' => function($choice) use ($translator) {
          /** @var Skill $choice */
          return $translator->trans($choice->getCategory(), [], 'character');
        },
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
