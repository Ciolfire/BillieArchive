<?php

namespace App\Form\Mage;

use App\Entity\MageOrder;
use App\Entity\MageSpell;
use App\Entity\SpellRote;
use App\Form\Type\SourceableForm;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellRoteForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var SpellRote */
    $rote = $options['data'];

    $builder
      ->add('name')
      ->add('attribute')
      ->add('description', null, [
        'attr' => [
          'rows' => '5',
        ]
    ]);
    if ($rote->getSpell()) {
      $builder
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
    } else if ($rote->getCreator()) {
      if ($rote->getCreator()->getOrder())
      $builder
        ->add('mageOrder', EntityType::class, [
          'class' => MageOrder::class,
          'choices' => [$rote->getCreator()->getOrder()],
          'choice_label' => "name",
          'required' => false,
        ])
      ;
      $builder
        ->add('spell', EntityType::class, [
          'class' => MageSpell::class,
          'choices' => $options['spells'],
          'choice_label' => "name",
          'required' => true,
        ])
      ;
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => SpellRote::class,
      'spells' => null,
    ]);
  }
}
