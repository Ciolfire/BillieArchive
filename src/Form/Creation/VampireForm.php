<?php

namespace App\Form\Creation;

use App\Entity\Attribute;
use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Vampire;
use App\Form\CharacterForm;
use App\Form\Type\RadiobuttonType;
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
    ->add('covenant', RadiobuttonType::class, [
      'empty_data' => null,
      'required' => false,
      'placeholder' => null,
      'choices' => $options['covenants'],
      'choice_label' => 'name',
      'choice_attr' => function(Covenant $covenant) {
        return [
          'data-organization' => "covenant-{$covenant->getId()}",
          'data-action' => 'click->character--embrace#covenantPicked',
          'data-character--embrace-target' => 'covenant'
        ];
      }
    ])
      ->add('deathAge', null, [
        'label' => false,
        'attr' => [
          'placeholder' => 'embrace.at'
        ],
        'translation_domain' => 'vampire',
      ])
      ->add('sire', null, [
        'label' => 'embrace.by',
        'translation_domain' => 'vampire',
        'required' => false,
      ])
      ->add('moral', RadiobuttonType::class, [
        'choices' => [7 => 7,6 => 6,5 => 5],
        'data' => 7,
      ])
      ->add('potency', RadiobuttonType::class, [
        'choices' => [
          1 => 1,
          2 => 2,
          3 => 3
        ],
        'empty_data' => 1,
        'choice_attr' => function ($choice, string $key, mixed $value) {
          return [
            "data-character--creation-target" => "templateTrait",
            "data-action" => "click->character--creation#meritUpdate",
            "data-value" => $value,
            "data-type" => "potency",
          ];
        },
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    parent::configureOptions($resolver);

    $resolver->setDefaults([
      "data_class" => Vampire::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "name" => "character",
      // "is_edit" => false,
      "clans" => null,
      "covenants" => null,
      "attributes" => null,
    ]);
  }
}