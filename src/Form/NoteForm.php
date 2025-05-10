<?php

namespace App\Form;

use App\Entity\Chronicle;
use App\Entity\Note;
use App\Form\Type\ExpandedEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NoteForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $path = $options['path'];
    /** @var Note $note */
    $note = $options['data'];
    $chronicle = $note->getChronicle();
    if ($chronicle instanceof Chronicle) {
      $character = $chronicle->getCharacter($note->getUser());
      $characters = $character->getKnownCharacters();
    } else {
      $characters = null;
    }
    
    $builder
      ->add('title', null, ['label' => 'title'])
      ->add('assignedAt', DateType::class, array(
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
        // 'data' => $date,
        'label' => 'date',
      ))
      ->add('content', RichTextEditorForm::class, [
        'label' => 'content',
        'empty_data' => '',
        'data' => $note->getContent()]
        )
      ->add('category', ChoiceType::class, [
        'label' => 'folder.label',
        'choices' => $options['categories'],
        'choice_label' => function ($choice) {
          return $choice->getName();
        },
        'choice_translation_domain' => false,
        'required' => false,
      ])
      ->add('character', null, [
        'label' => false,
        'expanded' => true,
        'translation_domain' => 'character',
        'attr' => ['class' => 'form-control d-flex flex-wrap'],
        'choices' => $characters,
        'choice_label' => function ($choice) use ($path, $character): string {
          $access = $character->getSpecificPeekingRights($choice);
          if (in_array('avatar', $access->getRights())) {
            $avatar = "{$path}/{$choice->getAvatar()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;";
          } else {
            $avatar = "{$path}/default.jpg";
          }
          $name = $choice->getPublicName($character);
          if ($name == "") {
            $name = "<span class=\"warning\">?</span>";
          }
          return "<div role=\"button\" class=\"d-inline-block me-1\"><img class=\"form-select-item-avatar\" src=\"$avatar\">$name</div>";
        },
        'label_attr' => ['class' => 'text me-2 form-choice-width text-truncate'],
        'label_html' => true,
      ])
      ->add('notes', ExpandedEntityType::class, [
        'class' => Note::class,
        'label' => 'links',
        'expanded' => true,
        'multiple' => true,
        'choices' => $options['notes'],
        'choice_label' => 'title',
        'choice_value' => 'id',
        'group_by' => function($choice, $key, $value) {
          if ($choice->getCategory()) {
            return $choice->getCategory();
          }
          return "";
        },
        'data' => $note->getNotes(),
        // 'mapped' => false,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Note::class,
      'translation_domain' => 'note',
      'categories' => [],
      'notes' => [],
      'path' => null,
    ]);
  }
}
