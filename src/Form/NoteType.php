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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twig\Extra\Markdown\LeagueMarkdown;

class NoteType extends AbstractType
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
    $converter = new LeagueMarkdown();
    $builder
      ->add('title', null, ['label' => 'title'])
      ->add('assignedAt', DateType::class, array(
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
        // 'data' => $date,
        'label' => 'date',
      ))
      ->add('content', CKEditorType::class, [
        'label' => 'content',
        'empty_data' => '',
        'data' => $converter->convert($note->getContent())]
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
        // 'label' => 'label.single',
        'translation_domain' => 'character',
        'expanded' => true,
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
            $name = "<span class=\"warning\">unknown</span>";
          }
          return "<div class=\"d-inline-block me-1\"><img class=\"form-select-item-avatar\" src=\"$avatar\">$name</div>";
        },
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
      ->add('save', SubmitType::class, ['label' => 'action.save', 'translation_domain' => 'app']);
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
