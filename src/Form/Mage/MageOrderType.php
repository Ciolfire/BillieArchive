<?php

namespace App\Form\Mage;

use App\Entity\MageOrder;
use App\Entity\Skill;
use App\Form\OrganizationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Dropzone\Form\DropzoneType;

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
      ->add('rune', DropzoneType::class, [
        'label' => 'rune.label',
        'translation_domain' => 'mage',
        'attr' => ['placeholder' => 'upload'],
        'mapped' => false,
        'required' => false,
      ])
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
