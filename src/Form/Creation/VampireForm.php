<?php

namespace App\Form\Creation;

use App\Entity\Attribute;
use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Vampire;
use App\Form\CharacterForm;
use App\Form\Type\RadiobuttonType;
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
      // ->add('clan', EntityType::class, [
      //   'class' => Clan::class,
      //   'choices' => $options['clans'],
      // ])
    ->add('clan', RadiobuttonType::class, [
      'choices' => $options['clans'],
      'choice_label' => 'name',
      'choice_attr' => function(Clan $clan) {
        $attributes = "";
        foreach ($clan->getAttributes()->toArray() as $attribute) {
          if ($attributes != "") {
            $attributes = "{$attributes} {$attribute->getIdentifier()}";
          } else {
            $attributes = "{$attribute->getIdentifier()}";
          }
        }
        $disciplines = "";
        foreach ($clan->getDisciplines()->toArray() as $discipline) {
          if ($disciplines != "") {
            $disciplines = "{$disciplines} {$discipline->getId()}";
          } else {
            $disciplines = "{$discipline->getId()}";
          }
        }

        return [
          'data-bs-toggle' => 'tooltip',
          'title' => $clan->getKeywords(),
          'data-clan' => $clan->getName(),
          'data-attributes' => $attributes,
          'data-disciplines' => $disciplines,
          'data-action' => 'click->character--embrace#clanPicked',
          'data-character--embrace-target' => 'clan'
        ];
      }
    ])
    ->add('attribute', RadiobuttonType::class, [
      'required' => true,
      'mapped' => false,
      'choices' => $options['attributes'],
      'choice_label' => 'name',
      'choice_attr' => function(Attribute $attribute) {
        return [
          'class' => "d-none {$attribute->getIdentifier()}",
          'data-character--embrace-target' => 'clanAttribute',
        ];
      }
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
      "attributes" => null,
    ]);
  }
}