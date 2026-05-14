<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\CharacterNote;
use App\Entity\Chronicle;
use App\Entity\Types\TypeNote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\Type\RichTextEditorForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CharacterNoteForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var CharacterNote $note */
    $note = $options['data'];
    
    $character = $note->getCharacter();
    $path = $options['path'];
    $characters = [];
    if ($character instanceof Character && $character->getChronicle() instanceof Chronicle) {
      $characters = $character->getChronicle()->getPlayerCharacters();
      $id = array_search($character, $characters);
      if ($id !== false) {
        unset($characters[$id]);
      }
    }

    $date = $note->getAssignedAt();
    if (is_null($date)) {
      $date = new \DateTimeImmutable($options['date']);
    }

    $builder
    ->add('title', null, ['label' => 'title'])
    ->add('assignedAt', DateType::class, array(
      'widget' => 'single_text',
      'input' => 'datetime_immutable',
      'data' => $date,
      'label' => 'date',
    ))
    ->add('content', RichTextEditorForm::class, [
      'label' => 'content',
      'empty_data' => '',
      'data' => $note->getContent()]
      )
    ->add('type', ChoiceType::class, [
      'choices' => TypeNote::typeChoices,
      'label' => 'type.label',
      'choice_label' => function ($choice, $key, $value) {
        return 'type.'.$key;
      }
    ])
    ->add('accessList', EntityType::class, [
        'label' => 'infos.details.access.list',
        'class' => Character::class,
        'choices' => $characters,
        'choice_label' => function ($choice, string $key, mixed $value) use ($path): string {
          return '<div class="d-inline-block me-1" style="width:40px;">'."<img height=\"40\" src=\"{$path}/{$choice->getAvatar()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;\"></div>".$choice->getName();
        },
        'label_html' => true,
        'expanded' => true,
        'multiple' => true,
        'translation_domain' => 'character',
        // 'attr' => ['class' => 'form-control d-flex flex-wrap'],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterNote::class,
      'date' => '2005-02-01 00:00',
      'translation_domain' => 'note',
      'path' => null,
    ]);
  }
}
