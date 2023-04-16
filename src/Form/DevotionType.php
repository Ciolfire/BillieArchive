<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Devotion;
use App\Entity\Discipline;
use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extra\Markdown\LeagueMarkdown;

class DevotionType extends AbstractType
{
  public TranslatorInterface $translator;

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
      ->add('name', null, ['label' => 'name', 'translation_domain' => 'app'])
      ->add('cost', null, ['label' => 'cost', 'translation_domain' => 'app'])
      ->add('description', CKEditorType::class, ['label' => false, 'empty_data' => '', 'data' => $converter->convert($devotion->getDescription())])
      ->add('short', null, ['label' => 'description.short.label', 'translation_domain' => 'app'])
      ->add('page', null, ['label' => 'page', 'translation_domain' => 'app'])
      ->add('bloodline', null, ['label' => 'clan.bloodline.label'])
      ->add('prerequisites', CollectionType::class, [
        'label' => false,
        'entry_type' => PrerequisiteType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
      ])
      ->add('disciplines', null, [
        'label' => false,
        'expanded' => true,
        'translation_domain' => "vampire",
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
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
      ->add('contestedText', null, ['label' => 'contested.text', 'translation_domain' => 'app'])
      ->add('homebrewFor', null, ['label' => 'homebrewFor', 'translation_domain' => 'app'])
      ->add('book', null, ['label' => 'book', 'translation_domain' => 'app']);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Devotion::class,
      "translation_domain" => 'vampire',

    ]);
  }
}
