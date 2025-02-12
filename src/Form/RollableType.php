<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Roll;
use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Contracts\Translation\TranslatorInterface;


class RollableType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    $translator = $this->translator;

    $builder
      ->add('action', ChoiceType::class, [
        'label' => 'action.label.single',
        'choices' => [
          'roll.action.instant' => 0,
          'roll.action.extended' => 1,
          'roll.action.reflexive' => 2,
        ]
      ])
      ->add('attributes', null, [
        'label' => 'label.single',
        'expanded' => true,
        'translation_domain' => "attribute",
        'group_by' => function ($choice) use ($translator) {
          /** @var Attribute $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
        ])
        ->add('skills', null, [
          'label' => 'label.multi',
          'expanded' => true,
          'translation_domain' => "skill",
          'choice_attr' => ['class' => 'text-sub'],
          'group_by' => function ($choice) use ($translator) {
            /** @var Skill $choice */
            return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
          },
          ])
      ->add('isContested', null, ['label' => "roll.action.contested"])
      ->add('contestedText', null, ['label' => "roll.action.text"])
      ->add('linked', HiddenType::class, ['empty_data' => true])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Roll::class,
      'translation_domain' => "app",
    ]);
  }
}
