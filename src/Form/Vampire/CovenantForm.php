<?php

namespace App\Form\Vampire;

use App\Entity\Covenant;
use App\Form\OrganizationForm;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CovenantForm extends OrganizationForm
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
      ->add('disciplines', null, [
        'label' => 'covenant.discipline.limited',
        'help' => 'covenant.help.discipline.limited',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('devotions', null, [
        'label' => 'covenant.devotion.limited',
        'help' => 'covenant.help.devotion.limited',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('merits', null, [
        'label' => 'covenant.merit.limited',
        'help' => 'covenant.help.merit.limited',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('discountMerits', null, [
        'label' => 'covenant.merit.discount',
        'help' => 'covenant.help.merit.discount',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'choice_label'  => function ($choice): string {
          if ($choice->getType())
            return "{$choice->getName()} — {$choice->getType()}";
          return $choice->getName();
        },
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Covenant::class,
      'translation_domain' => 'organization',
      'item' => null,
    ]);
  }
}
