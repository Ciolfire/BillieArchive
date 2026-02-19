<?php

namespace App\Form\Creation;

use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Vampire;
use App\Form\CharacterForm;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VampireForm extends CharacterForm
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    // The user is the one creating the character
    /** @var Vampire $vampire */
    $vampire = $options['data'];
    $chronicle = $vampire->getchronicle();
    $options['user'] = $vampire->getPlayer();
    parent::buildForm($builder, $options);

    $builder
      ->add('clan', EntityType::class, [
        'class' => Clan::class,
        'choices' => $options['clans'],
      ])
      ->add('deathAge', null, [
        'label' => false,
        'attr' => [
          'placeholder' => 'embrace.at'
        ],
        'translation_domain' => 'vampire',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    parent::configureOptions($resolver);

    $resolver->setDefaults([
      "data_class" => Vampire::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      // "name" => "human",
      // "is_edit" => false,
      "clans" => null,
    ]);
  }
}