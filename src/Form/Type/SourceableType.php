<?php
namespace App\Form\Type;

use App\Entity\Book;
use App\Entity\Chronicle;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SourceableType  extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('book', EntityType::class, [
        'label' => 'label.single',
        'class' => Book::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('b')->addOrderBy('b.setting', 'ASC')->addOrderBy('b.displayFirst', 'DESC')->addOrderBy('b.name', 'ASC');
        },
        'group_by' => function ($choice) {
          return $this->translator->trans("{$choice->getSetting()}.label", [], 'setting');
        },
        'required' => false,
      ])
      ->add('page', null, [
        'label' => 'page',
        'row_attr' => [
          'style' => 'display:none;',
        ],
      ])
      ->add('homebrewFor', EntityType::class, [
        'class' => Chronicle::class,
        'choice_label' => function ($choice) {
          return $this->translator->trans("{$choice->getType()}.element", ['element' => $choice], 'setting');
        },
        'label' => 'homebrew',
        'translation_domain' => 'app',
        'required' => false,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
      $resolver->setDefaults([
          'inherit_data' => true,
          'translation_domain' => 'book',
      ]);
  }
}
