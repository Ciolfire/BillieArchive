<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twig\Extra\Markdown\LeagueMarkdown;

class NoteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Note $note */
    $note = $options['data'];
    $chronicle = $note->getChronicle();
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
        'label' => 'category.label',
        'choices' => $options['categories'],
        'choice_label' => function ($choice) {
          return $choice->getName();
        }
      ])
      ->add('character', null, [
        'label' => 'character.label',
        'choices' => $chronicle->getCharacters(),
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
    ]);
  }
}
