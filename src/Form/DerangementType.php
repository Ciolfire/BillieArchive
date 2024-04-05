<?php declare(strict_types=1);

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
  public TranslatorInterface $translator;

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
      ->add('name', null, ['label' => 'name'])
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($element->getDetails()), 'label' => false])
      ->add('type', null, ['label' => 'type'])
      ->add('isExtreme', null, ['label' => 'derangement.extreme'])
      ->add('previousAilment', null, [
        'label' => 'derangement.previous',
        'choice_filter' => function (?Derangement $derangement) {
          return $derangement ? is_null($derangement->getPreviousAilment()) : true;
        },
        'choice_label' => function (?Derangement $derangement) {
          if ($derangement->getType()) {
            return "{$derangement->getName()} — {$derangement->getType()}";
          }
          return $derangement->getName();
        },
      ])
      ->add('book', null, ['label' => 'book'])
      ->add('page', null, ['label' => 'page'])
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
