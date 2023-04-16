<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\CharacterNote;
use App\Entity\Types\TypeNote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;


class CharacterNoteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var CharacterNote $note */
    $note = $options['data'];
    $converter = new LeagueMarkdown();
    $date = $note->getAssignedAt();
    if (is_null($date)) {
      $date = new \DateTimeImmutable($options['date']);
    }

    $builder
    ->add('title', null, ['label' => 'note.title'])
    ->add('assignedAt', DateType::class, array(
      'widget' => 'single_text',
      'input' => 'datetime_immutable',
      'data' => $date,
      'label' => 'note.date',
    ))
    ->add('content', CKEditorType::class, [
      'label' => 'note.content',
      'empty_data' => '',
      'data' => $converter->convert($note->getContent())]
      )
    ->add('type', ChoiceType::class, [
      'choices' => TypeNote::typeChoices,
      'label' => 'note.type.label',
      'choice_label' => function ($choice, $key, $value) {
        return 'note.type.'.$key;
      }
    ])
    ->add('save', SubmitType::class, ['label' => 'action.save']);
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterNote::class,
      'date' => '2005-02-01 00:00',
      'translation_domain' => 'app',
    ]);
  }
}
