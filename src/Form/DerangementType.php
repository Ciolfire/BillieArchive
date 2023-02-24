<?php

namespace App\Form;

use App\Entity\Derangement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Twig\Extra\Markdown\LeagueMarkdown;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Contracts\Translation\TranslatorInterface;

class DerangementType extends AbstractType
{
  public $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    $translator = $this->translator;

    /** @var Derangement */
    $element = $options['data'];

    $builder
      ->add('name')
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($element->getDetails()), 'label' => false])
      ->add('type')
      ->add('isExtreme')
      ->add('previousAilment')
      ->add('type')
      ->add('book')
      ->add('page')
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Derangement::class,
      'translation_domain' => "app",
    ]);
  }
}
