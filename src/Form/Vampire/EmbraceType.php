<?php declare(strict_types=1);

namespace App\Form\Vampire;

use App\Entity\Clan;
use App\Entity\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RadiobuttonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmbraceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
    ->add('sire', TextType::class, [
      'label' => 'embrace.by',
      'required' => false,
    ])
    ->add('age', IntegerType::class, [
      'label' => 'embrace.at',
      'required' => false,
    ])
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
      'choices' => $options['attributes'],
      'choice_label' => 'name',
      'choice_attr' => function(Attribute $attribute) {
        return [
          'class' => "d-none {$attribute->getIdentifier()}",
          'data-character--embrace-target' => 'clanAttribute',
        ];
      }
    ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'clans' => null,
      'attributes' => null,
      'translation_domain' => 'vampire',
      "allow_extra_fields" => true,
    ]);
  }
}
