<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Item;
use App\Entity\Items\Equipment;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extra\Markdown\LeagueMarkdown;

class EquipmentType extends ItemType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    parent::buildForm($builder, $options);
    
    $item = $options['data'];

    $builder
      ->add('quality', ChoiceType::class, [
        'label' => 'quality.label',
        'help' => 'help.quality',
        'choices' => [
            'quality.normal' => array_combine(range(0, 5), range(0, 5)),
            'quality.surnatural' => array_combine(range(6, 10), range(6, 10)),
        ],
      ])
      ->add('functionality', CKEditorType::class, [
        'label' => 'functionality.label',
        'empty_data' => '',
        'data' => $converter->convert($item->getFunctionality()),
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Equipment::class,
      'translation_domain' => 'item',
    ]);
  }
}
