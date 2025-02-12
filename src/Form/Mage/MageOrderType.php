<?php

namespace App\Form\Mage;

use App\Entity\MageOrder;
use App\Entity\Skill;
use App\Form\OrganizationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MageOrderType extends OrganizationType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    parent::buildForm($builder, $options);

    // $item = $options['data'];

    $builder
      ->add('RoteSpecialties', EntityType::class, [
        'class' => Skill::class,
        'choice_label' => 'id',
        'multiple' => true,
        'expanded' => true,
        'choice_label'  => function ($choice): string {
          return $choice->getName();
        },
        'group_by' => function($choice) {
          /** @var Skill $choice */
          return $this->translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => MageOrder::class,
      'translation_domain' => 'organization',
      'item' => null,
    ]);
  }
}
