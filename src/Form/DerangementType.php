<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Derangement;
use App\Form\Type\ContentTypeType;
use App\Form\Type\SourceableType;
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
      ->add('name', null, ['label' => 'label.single'])
      ->add('type', ContentTypeType::class, [
        'data_class' => Derangement::class,
        'label' => false,
      ])
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($element->getDetails()), 'label' => false])
      ->add('isExtreme', null, ['label' => 'extreme', 'help' => 'help.extreme'])
      ->add('previousAilment', null, [
        'label' => 'previous',
        'help' => 'help.previous',
        'choice_filter' => function (?Derangement $derangement) {
          return $derangement ? is_null($derangement->getPreviousAilment()) : true;
        },
        'choice_label' => function (?Derangement $derangement) {
          if ($derangement->getType()) {
            $type = $this->translator->trans($derangement->getType()->getName(), [], 'content-type');
            return "{$derangement->getName()} — {$type}";
          }
          return $derangement->getName();
        },
      ])
      ->add('source', SourceableType::class, [
        'data_class' => Derangement::class,
        'label' => 'source.label',
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Derangement::class,
      'translation_domain' => "derangement",
    ]);
  }
}
