<?php

namespace App\Form;

use App\Entity\Chronicle;
use App\Entity\User;

use App\Form\CharacterSpecialtyForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChronicleForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('type', ChoiceType::class, [
        'choices' => [
          'human' => 'human',
          'vampire' => 'vampire',
          'werewolf' => 'werewolf',
          'mage' => 'mage',
        ],
        'choice_translation_domain' => 'content-type',
      ])
      ->add('startAt')
      ->add('currentlyAt')
      ->add('save', SubmitType::class);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Chronicle::class,
      "translation_domain" => 'chronicle',
    ]);
  }
}
