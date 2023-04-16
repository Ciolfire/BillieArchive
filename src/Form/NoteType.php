<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Chronicle;
use App\Entity\Note;
use App\Form\Type\ExpandedEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twig\Extra\Markdown\LeagueMarkdown;

class NoteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    // dd($options['notes']);
    /** @var Note $note */
    $note = $options['data'];
    $chronicle = $note->getChronicle();
    if ($chronicle instanceof Chronicle) {
      $characters = $chronicle->getCharacters();
    } else {
      $characters = null;
    }
    $converter = new LeagueMarkdown();
    $builder
      ->add('title', null, ['label' => 'note.title'])
      ->add('assignedAt', DateType::class, array(
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
        // 'data' => $date,
        'label' => 'note.date',
      ))
      ->add('content', CKEditorType::class, [
        'label' => 'note.content',
        'empty_data' => '',
        'data' => $converter->convert($note->getContent())]
        )
      ->add('category', ChoiceType::class, [
        'label' => 'note.category.label',
        'choices' => $options['categories'],
        'required' => false,
        'choice_label' => function ($choice) {
          return $choice->getName();
        },
        'choice_translation_domain' => false,
      ])
      ->add('character', null, [
        'label' => 'character.label',
        'choices' => $characters,
      ])
      ->add('notes', ExpandedEntityType::class, [
        'class' => Note::class,
        'label' => 'note.links',
        'expanded' => true,
        'multiple' => true,
        'choices' => $options['notes'],
        'choice_label' => 'title',
        'choice_value' => 'id',
        'group_by' => function($choice, $key, $value) {
          return $choice->getCategory();
        },
        'data' => $note->getNotes(),
        // 'mapped' => false,
      ])
      ->add('save', SubmitType::class, ['label' => 'action.save']);
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Note::class,
      'translation_domain' => 'app',
      'categories' => [],
      'notes' => [],
    ]);
  }
}
