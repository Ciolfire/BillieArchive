<?php

namespace App\Form\Creation;

use App\Entity\Arcanum;
use App\Entity\Path;
use App\Entity\MageOrder;
use App\Entity\Mage;
use App\Form\CharacterForm;
use App\Form\Type\RadiobuttonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MageForm extends CharacterForm
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Mage $mage */
    // $mage = $options['data'];
    parent::buildForm($builder, $options);

    $builder
      ->add('path', RadiobuttonType::class, [
        'choices' => $options['paths'],
        'choice_label' => 'name',
        'choice_attr' => function (Path $path) {
          $arcana = "";
          foreach ($path->getRulingArcana() as $arcanum) {
            $arcana = "{$arcana} {$arcanum->getId()}";
          }

          return [
            'data-bs-toggle' => 'tooltip',
            'title' => $path->getTitle(),
            'data-path' => $path->getName(),
            'data-ruling-arcana' => $arcana,
            'data-inferior-arcanum' => $path->getInferiorArcanum(),
            'data-action' => 'click->character--awakening#pathPicked',
            'data-character--awakening-target' => 'path'
          ];
        }
      ])
      ->add('order', RadiobuttonType::class, [
        'empty_data' => null,
        'required' => false,
        'placeholder' => null,
        'choices' => $options['orders'],
        'choice_label' => 'name',
        'choice_attr' => function (MageOrder $order) {
          return [
            'data-order' => "mage-order-{$order->getId()}",
            'data-action' => 'click->character--awakening#orderPicked',
            'data-character--awakening-target' => 'order'
          ];
        }
      ])
      ->add('moral', RadiobuttonType::class, [
        'choices' => [7 => 7, 6 => 6, 5 => 5],
        'data' => 7,
      ])
      ->add('gnosis', RadiobuttonType::class, [
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
            "data-type" => "gnosis",
          ];
        },
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    parent::configureOptions($resolver);

    $resolver->setDefaults([
      "data_class" => Mage::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "name" => "character",
      "paths" => null,
      "orders" => null,
    ]);
  }
}
