<?php

namespace App\Form;

use App\Entity\Clan;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClanType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Clan */
    $clan = $options['data'];
    $builder
      ->add('name')
      ->add('parentClan')
      ->add('attributes', null, ['expanded' => true])
      ->add('disciplines', null, ['expanded' => true])
      ->add('short')
      ->add('description')
      ->add('keywords')
      ->add('book')
      ->add('page')
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Clan::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "is_edit" => false,
    ]);
  }
}
