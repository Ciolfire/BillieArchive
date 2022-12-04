<?php

namespace App\Form;

use App\Entity\Clan;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class ClanType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Clan */
    $clan = $options['data'];
    $converter = new LeagueMarkdown();

    $builder
      ->add('name')
      ->add('nickname')
      ->add('quote')
      ->add('name')
      ->add('emblem', FileType::class, [
        'label' => 'file',
        'translation_domain' => 'app',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File([
            'mimeTypes' => [
              'image/*',
            ],
            'mimeTypesMessage' => 'image Invalid',
          ])
        ],
      ])
      ->add('parentClan', null, ['choice_filter' => function (?Clan $clan) {
        return $clan ? $clan->isBloodline() : false;
      }])
      ->add('attributes', null, ['expanded' => true])
      ->add('disciplines', null, ['expanded' => true])
      ->add('short')
      ->add('description', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($clan->getDescription()), 'label' => false])
      ->add('weakness', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($clan->getWeakness())])
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
