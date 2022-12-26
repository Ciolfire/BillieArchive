<?php

namespace App\Form;

use App\Entity\Clan;
use Doctrine\ORM\EntityRepository;
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
      ->add('name', null, ['label' => "name"])
      ->add('quote', null, ['label' => "quote"])
      ->add('book', null, ['label' => "book"])
      ->add('page', null, ['label' => "page"])
      ->add('emblem', FileType::class, [
        'label' => 'emblem',
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
      ->add('nickname', null, ['label' => "nickname"])
      ->add('weakness', CKEditorType::class, [
        'label' => 'clan.weakness',
        'translation_domain' => 'vampire',
        'empty_data' => '',
        'data' => $converter->convert($clan->getWeakness())
      ])
      ->add('short', null, ['label' => "description.short.label"])
      ->add('description', CKEditorType::class, [
        'empty_data' => '', 
        'data' => $converter->convert($clan->getDescription()), 
        'label' => "description.label",
      ])
      ->add('disciplines', null, [
        'expanded' => true,
        'attr' => ['class' => 'form-control'],
        'label_attr' => ['class' => 'text'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
        'label' => 'disciplines.label',
        'translation_domain' => 'vampire',
      ])
      ;
    if ($clan->isBloodline()) {
      $builder->add('parentClan', null, [
          'label' => 'clan.parent.label',
          'translation_domain' => 'vampire',
          'choice_filter' => function (?Clan $clan) {
            return $clan ? !$clan->isBloodline() : false;
          }
        ]);
    } else {
      $builder->add('attributes', null, ['expanded' => true, 'label' => 'attributes']);
    }
    $builder
      ->add('homebrewFor', null, ['label' => "chronicle.label"])
      ->add('keywords', null, ['label' => 'keywords'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Clan::class,
      "translation_domain" => 'app',
      "allow_extra_fields" => true,
      "is_edit" => false,
    ]);
  }
}
