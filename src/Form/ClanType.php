<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Clan;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
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
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Clan */
    $clan = $options['data'];
    $converter = new LeagueMarkdown();
    $translator = $this->translator;

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('quote', null, ['label' => "quote", 'translation_domain' => "app"])
      ->add('book', null, ['label' => "book", 'translation_domain' => "app"])
      ->add('page', null, ['label' => "page", 'translation_domain' => "app"])
      ->add('emblem', FileType::class, [
        'label' => 'emblem',
        'mapped' => false,
        'required' => false,
        'translation_domain' => "app",
        'constraints' => [
          new File([
            'mimeTypes' => [
              'image/*',
            ],
            'mimeTypesMessage' => 'image Invalid',
          ])
        ],
      ])
      ->add('description', CKEditorType::class, [
        'empty_data' => '',
        'data' => $converter->convert($clan->getDescription()), 
        'label' => "description.label",
        'translation_domain' => "app",
      ]);
      if ($clan->isBloodline()) {
        $builder->add('parentClan', null, [
            'label' => 'clan.parent.label',
            'translation_domain' => 'vampire',
            'choice_filter' => function (?Clan $clan) {
              return $clan ? !$clan->isBloodline() : false;
            }
          ]
        );
      } else {
        $builder->add('attributes', null, [
          'expanded' => true,
          'translation_domain' => "app",
          'label' => 'attributes',
          'group_by' => function($choice) use ($translator) {
            /** @var Attribute $choice */
            return $translator->trans($choice->getCategory(), [], 'character');
          },
        ]);
      }
      $builder->add('nickname', null, ['label' => "nickname", 'translation_domain' => 'app'])
      ->add('short', null, ['label' => "description.short.label", 'translation_domain' => 'app'])
      ->add('weakness', CKEditorType::class, [
        'label' => 'clan.weakness',
        'translation_domain' => 'vampire',
        'empty_data' => '',
        'data' => $converter->convert($clan->getWeakness())
      ])
      ->add('disciplines', null, [
        'label' => 'disciplines.label',
        'expanded' => true,
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'label_attr' => ['class' => 'text pe-2 form-choice-width'],
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
        },
      ])
      ->add('homebrewFor', null, ['label' => "chronicle.label", 'translation_domain' => 'app'])
      ->add('keywords', null, ['label' => 'keywords', 'translation_domain' => 'app'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Clan::class,
      "translation_domain" => 'vampire',
      "allow_extra_fields" => true,
      "is_edit" => false,
    ]);
  }
}
