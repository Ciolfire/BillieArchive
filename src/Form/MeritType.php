<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\ContentType;
use App\Entity\Merit;
use App\Form\Type\SourceableType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
      ->add('type', EntityType::class, [
        'label' => "type.label",
        'class' => ContentType::class,
        'choice_label' => function ($choice): string {
          return 'type.'.$choice->getName();
        },
        'choice_translation_domain' => 'app',
        'query_builder' => function (EntityRepository $er): QueryBuilder {
          return $er->createQueryBuilder('ct')
              ->orderBy('ct.name', 'ASC');
        },
        'required' => false,
        'empty_data' => '',
      ])
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
        'entry_options' => ['label' => false, 'type' => 'merit'],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('source', SourceableType::class, [
        'data_class' => Merit::class,
        'label' => 'source.label',
      ])
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
