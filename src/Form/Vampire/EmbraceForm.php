<?php

namespace App\Form\Vampire;

use App\Entity\Clan;
use App\Entity\Attribute;
use App\Entity\Covenant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RadiobuttonForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmbraceForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
    ->add('age', IntegerType::class, [
      'label' => 'embrace.at',
      'translation_domain' => 'vampire',
      'required' => false,
    ])
    ->add('sire', TextType::class, [
      'label' => 'embrace.by',
      'translation_domain' => 'vampire',
      'required' => false,
    ])
    ->add('clan', RadiobuttonForm::class, [
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
    ->add('attribute', RadiobuttonForm::class, [
      'required' => true,
      'choices' => $options['attributes'],
      'choice_label' => 'name',
      'choice_attr' => function(Attribute $attribute) {
        return [
          'class' => "d-none {$attribute->getIdentifier()}",
          'data-character--embrace-target' => 'clanAttribute',
        ];
      }
    ])
    ->add('covenant', RadiobuttonForm::class, [
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
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'clans' => null,
      'covenants' => null,
      'attributes' => null,
      'translation_domain' => false,
      "allow_extra_fields" => true,
    ]);
  }
}
