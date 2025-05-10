<?php

namespace App\Form;

use App\Entity\Arcanum;
use App\Entity\MageSpellArcanum;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MageSpellArcanumType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('arcanum', EntityType::class, [
        'class' => Arcanum::class,
        'choice_label' => 'name',
        'label' => "label.single",
        'translation_domain' => "arcanum",
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ;
        },
      ])
      ->add('level', null, [
        'label' => "level",
        'attr' => [
          'min' => 1,
          'max' => 10,
        ]
      ])
      ->add('choiceGroup', null, [
        'label' => "choice.label",
        'help' => "help.choice",
        'attr' => [
          'min' => 1,
        ]
      ])
      ->add('isOptional', null, [
        'label' => 'optional',
      ])
      ->add('remove', ButtonType::class, [
        'attr' => [
          'data-action' => 'form-collection#removeCollectionElement',
          'class' => 'btn-warning w-25',
        ],
        'row_attr' => [
          'class' => 'text-center',
        ],
        'label' => 'action.remove',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => MageSpellArcanum::class,
      'empty_data' => function (FormInterface $form, $data): ?MageSpellArcanum {
        return new MageSpellArcanum($form->getParent()->getParent()->getData());
      },
      'translation_domain' => "app",
      'attr' => [
        'data-form-collection-target' => 'block',
        'class' => "bdr p-2 rounded",
      ],
    ]);
  }
}
