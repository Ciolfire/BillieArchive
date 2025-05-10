<?php

namespace App\Form\Mage;

use App\Entity\MageOrder;
use App\Entity\SpellRote;
use App\Form\Type\SourceableForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellRoteForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('attribute')
      ->add('description', null, [
        'attr' => [
          'rows' => '5',
        ]
      ])
      ->add('mageOrder', EntityType::class, [
        'class' => MageOrder::class,
        'choice_label' => "name",
        'required' => false,
      ])
      ->add('source', SourceableForm::class, [
        'data_class' => SpellRote::class,
        'label' => "source.label",
        'translation_domain' => "book",
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => SpellRote::class,
    ]);
  }
}
